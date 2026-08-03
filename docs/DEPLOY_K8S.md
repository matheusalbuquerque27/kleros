# Deploy do Kleros no Kubernetes (k3s)

Este documento cobre a arquitetura containerizada e o **runbook de migração** do
deploy bare-metal (`/var/www/kleros` + nginx/php-fpm do host) para o cluster k3s
que já roda no mesmo VPS.

Para operação do dia a dia (logs, artisan, rollback, backup), ver `k8s/README.md`.
Para o registro de como a migração foi feita — o que existia antes, o que mudou
em cada parte e os problemas encontrados — ver `MIGRACAO_KUBERNETES.md`.

---

## Arquitetura

```
  Internet
     │  https://kleros.app  +  https://*.kleros.app
     ▼
  nginx do host  ── TLS (Let's Encrypt wildcard kleros.app-0001)
     │  proxy_pass http://127.0.0.1:30311   (Host + X-Forwarded-* preservados)
     ▼
  Service kleros-main (NodePort 30311)
     ▼
  ┌──────────────────────────────────────────────────────────────┐
  │ namespace: kleros                                            │
  │                                                              │
  │  kleros-main       nginx + php-fpm 8.2 (supervisord)         │
  │    initContainer   php artisan migrate --force               │
  │  kleros-worker     php artisan queue:work database           │
  │  kleros-scheduler  CronJob */1  php artisan schedule:run     │
  │  kleros-db         mysql:8.0  (ClusterIP kleros-db:3306)     │
  │                                                              │
  │  PVCs: kleros-storage (uploads + backups) · kleros-db-data   │
  └──────────────────────────────────────────────────────────────┘
```

Uma única imagem (`ghcr.io/matheusffalbuquerque/kleros`) serve os quatro
workloads — worker, scheduler e o initContainer sobrescrevem o `command`.

### Por que não há Ingress

O cluster não usa Ingress: `kleroshub`, `duma`, `globusdei` e `talanta` já entram
pelo nginx do host, que é quem tem os certificados. O Kleros segue o mesmo padrão.

### Pontos de atenção do design

| Tema | Decisão |
|---|---|
| Multi-tenant por domínio | O nginx do container é `default_server` (aceita qualquer Host); o nginx do host **precisa** de `proxy_set_header Host $host` |
| HTTPS atrás de proxy | `bootstrap/app.php` confia nos `X-Forwarded-*` (`trustProxies`) — sem isso, `SESSION_SECURE_COOKIE=true` quebra |
| Sessão/cache/fila | Todos em `database` — não há Redis, o único estado é MySQL + PVC de uploads |
| 1 réplica no `kleros-main` | PVC `local-path` é RWO. Escalar exige mover o disco `public` para S3 |
| `config:cache` no entrypoint | Alterar o ConfigMap exige `rollout restart` |
| PDF | `LARAVEL_PDF_DRIVER=dompdf`; a imagem **não** tem Chrome/Browsershot |
| Deploy sem downtime | `RollingUpdate` com `maxUnavailable: 0` + `preStop` + **sem `keepalive` no upstream do nginx** (ver abaixo) |

### Por que o upstream do nginx não usa `keepalive`

Medido em produção: com `keepalive 16` no bloco `upstream`, todo rollout do
`kleros-main` derrubava ~3% dos requests (502 e conexões recusadas). O nginx do
host mantém conexões TCP persistentes para o pod antigo; elas sobrevivem à
remoção do endpoint do Service e estouram quando o pod morre — o `preStop`
sozinho não resolve, porque o problema não é o timing e sim a conexão presa.

Sem `keepalive`, cada request abre conexão nova e o conntrack escolhe um pod
`Ready` no momento. Em loopback o custo do handshake é irrelevante. Depois da
mudança: **245 requests em dois rollouts consecutivos, zero falhas.**

---

## Runbook de migração

### Fase 0 — Preparação (sem downtime)

```bash
ssh 72.61.60.208

# Disco está em ~80%. Os backups antigos ocupam ~1.9 GB.
du -sh /var/www/kleros/storage/backups
ls -lh /var/www/kleros/storage/backups/database
# remova os dumps que não interessam antes de criar os PVCs

# Namespace + credencial do GHCR (copiada do kleroshub)
kubectl apply -f -  <<'EOF'
apiVersion: v1
kind: Namespace
metadata:
  name: kleros
EOF

kubectl -n kleroshub get secret ghcr-credentials -o yaml \
  | sed 's/namespace: kleroshub/namespace: kleros/' \
  | kubectl apply -f -
```

**GitHub Secrets** que o workflow espera (Settings → Secrets → Actions):
`KUBE_CONFIG` (kubeconfig em base64, o mesmo do kleroshub), `APP_KEY`,
`DB_USERNAME`, `DB_PASSWORD`, `MYSQL_ROOT_PASSWORD`, `MAIL_USERNAME`,
`MAIL_PASSWORD`, `GOOGLE_MAPS_KEY`, `GOOGLE_MAPS_ID`.

> **`APP_KEY` tem que ser exatamente o de `/var/www/kleros/.env`.** Com uma chave
> diferente, todas as sessões e qualquer dado encriptado existente quebram.

### Fase 1 — Subir o ambiente em paralelo

Push na `main` dispara `.github/workflows/deploy.yml`: build da imagem no GHCR,
`kubectl apply` dos manifests e rollout. Ou manualmente, ver `k8s/README.md`.

```bash
kubectl -n kleros get pods,svc,pvc
kubectl -n kleros logs deploy/kleros-main --tail=100
```

### Fase 2 — Migrar os dados

```bash
# 1. Dump do MySQL do host
mysqldump -u kleros_user -p --single-transaction --routines --triggers \
  kleros_db > /tmp/kleros_cut.sql

# 2. Importar no pod (o initContainer já criou o schema; o dump sobrescreve)
kubectl -n kleros exec -i deploy/kleros-db -- \
  mysql -u root -p"$ROOT_PW" kleros_db < /tmp/kleros_cut.sql

# 3. Conferir
kubectl -n kleros exec deploy/kleros-db -- \
  mysql -u root -p"$ROOT_PW" -N -e \
  "SELECT (SELECT COUNT(*) FROM kleros_db.membros), (SELECT COUNT(*) FROM kleros_db.congregacoes);"
mysql -u kleros_user -p -N -e \
  "SELECT (SELECT COUNT(*) FROM kleros_db.membros), (SELECT COUNT(*) FROM kleros_db.congregacoes);"

# 4. Uploads (~16 MB) — fotos, logos, anexos, Drive
POD=$(kubectl -n kleros get pod -l app=kleros-main -o name | head -1)
kubectl -n kleros cp /var/www/kleros/storage/app/public \
  "${POD#pod/}:/var/www/html/storage/app/"

# 5. Migrations novas em cima do dump importado
kubectl -n kleros exec deploy/kleros-main -- php artisan migrate --force
```

### Fase 3 — Validar antes de virar

Vhost temporário no host (`/etc/nginx/sites-available/kleros-k8s`, com symlink em
`sites-enabled/`) — mesmo cert wildcard:

```nginx
server {
    listen 443 ssl;
    server_name k8s.kleros.app;

    ssl_certificate     /etc/letsencrypt/live/kleros.app-0001/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/kleros.app-0001/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    client_max_body_size 100M;

    location / {
        proxy_pass http://127.0.0.1:30311;
        proxy_set_header Host              $host;
        proxy_set_header X-Real-IP         $remote_addr;
        proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 120s;
    }
}
```

Checklist de validação:

- [ ] `curl -I https://k8s.kleros.app/up` → 200
- [ ] Login e sessão persistindo entre requests
- [ ] Um subdomínio real de congregação resolvendo o tema/logo certo
- [ ] Upload de foto de membro (grava no PVC)
- [ ] Geração de PDF de relatório (dompdf)
- [ ] Módulo Drive listando e baixando arquivos
- [ ] `kubectl -n kleros logs -l app=kleros-scheduler` mostrando ticks
- [ ] `kubectl -n kleros exec deploy/kleros-main -- php artisan db:backup`

### Fase 4 — Cutover

```bash
# 1. Congelar o app antigo
cd /var/www/kleros && php artisan down

# 2. Re-sincronizar o delta (dump + uploads) — repetir a Fase 2

# 3. Guardar o vhost atual e trocar por proxy_pass
cp /etc/nginx/sites-available/kleros /etc/nginx/sites-available/kleros.pre-k8s.bak
# nos DOIS server blocks 443 (kleros.app e *.kleros.app):
#   - remover `root /var/www/kleros/public` e o `location ~ \.php$`
#   - adicionar o mesmo bloco `location /` com proxy_pass do vhost de validação
#   - manter `client_max_body_size 100M` e o bloco /phpmyadmin (segue no host)
nginx -t && systemctl reload nginx

# 4. Desligar os processos antigos — SEM ISSO roda tudo em duplicidade
#    (dois schedulers = dois backups e dois e-mails de aniversário)
crontab -e     # remover: * * * * * cd /var/www/kleros && php artisan schedule:run
pkill -f "kleros/artisan queue:work"

# 5. Smoke test
curl -I https://kleros.app
```

### Rollback

```bash
cp /etc/nginx/sites-available/kleros.pre-k8s.bak /etc/nginx/sites-available/kleros
nginx -t && systemctl reload nginx
cd /var/www/kleros && php artisan up
# restaurar o crontab e o queue worker
```

Manter `/var/www/kleros` e o MySQL do host intactos por ~1 semana. Depois disso,
o `deploy.sh` e o fluxo de `git pull` no servidor podem ser aposentados
(continuam válidos apenas para o ambiente `klerostest`).

---

## Testar a imagem localmente

```bash
docker build -t kleros:test .

docker network create kleros-test
docker run -d --name kleros-db-test --network kleros-test \
  -e MYSQL_ROOT_PASSWORD=roottest -e MYSQL_DATABASE=kleros_db \
  -e MYSQL_USER=kleros_user -e MYSQL_PASSWORD=usertest mysql:8.0

docker run -d --name kleros-app-test --network kleros-test -p 8099:80 \
  -e APP_KEY="base64:$(openssl rand -base64 32)" \
  -e APP_ENV=production -e LOG_CHANNEL=stderr \
  -e DB_CONNECTION=mysql -e DB_HOST=kleros-db-test -e DB_DATABASE=kleros_db \
  -e DB_USERNAME=kleros_user -e DB_PASSWORD=usertest \
  -e SESSION_DRIVER=database -e CACHE_STORE=database -e QUEUE_CONNECTION=database \
  kleros:test

docker exec kleros-app-test php artisan migrate --force
curl -I http://localhost:8099/up
curl -H 'Host: kleros.app' -H 'X-Forwarded-Proto: https' http://localhost:8099/
```

Com o banco vazio o `/login` retorna 500 (`nome_curto` de congregação nula) —
isso é comportamento da aplicação sem dados, não da imagem.
