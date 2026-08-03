# Kleros no Kubernetes (k3s — namespace `kleros`)

Manifests do deploy de produção. O cluster é um k3s de node único (`srv1158721`,
`72.61.60.208`), sem Ingress: o **nginx do host** termina TLS e faz `proxy_pass`
para o NodePort do Service.

## Workloads

| Objeto | Tipo | Papel |
|---|---|---|
| `kleros-main` | Deployment | nginx + php-fpm (a app). Service **NodePort 30311** |
| `kleros-db` | Deployment | MySQL 8.0, PVC `kleros-db-data`. Service ClusterIP `kleros-db:3306` |
| `kleros-worker` | Deployment | `queue:work database` |
| `kleros-scheduler` | CronJob `* * * * *` | `schedule:run` (substitui o crontab do host) |

Os quatro usam a **mesma imagem** (`ghcr.io/matheusffalbuquerque/kleros`); worker,
scheduler e o initContainer de migration sobrescrevem o `command`.

## Pré-requisitos (uma vez)

```bash
# 1. Namespace
kubectl apply -f k8s/namespace.yml

# 2. Credencial do GHCR (copiada do namespace kleroshub)
kubectl -n kleroshub get secret ghcr-credentials -o yaml \
  | sed 's/namespace: kleroshub/namespace: kleros/' \
  | kubectl apply -f -

# 3. Secrets da aplicação — APP_KEY tem que ser o MESMO de /var/www/kleros/.env
#    (senão sessões e dados encriptados existentes quebram)
kubectl -n kleros create secret generic kleros-secrets \
  --from-literal=APP_KEY="$(grep '^APP_KEY=' /var/www/kleros/.env | cut -d= -f2-)" \
  --from-literal=DB_USERNAME=... \
  --from-literal=DB_PASSWORD=... \
  --from-literal=MYSQL_ROOT_PASSWORD=... \
  --from-literal=MAIL_USERNAME=... \
  --from-literal=MAIL_PASSWORD=... \
  --from-literal=GOOGLE_MAPS_KEY=... \
  --from-literal=GOOGLE_MAPS_ID=... \
  --dry-run=client -o yaml | kubectl apply -f -
```

Depois disso o deploy é automático via `.github/workflows/deploy.yml` (push na `main`).

## Apply manual (ordem importa)

```bash
kubectl apply -f k8s/namespace.yml
kubectl apply -f k8s/configmap.yml
kubectl apply -f k8s/pvc.yml
kubectl apply -f k8s/db-deployment.yml -f k8s/db-service.yml
kubectl -n kleros rollout status deploy/kleros-db          # espere o MySQL subir
kubectl apply -f k8s/deployment.yml -f k8s/service.yml
kubectl apply -f k8s/worker-deployment.yml
kubectl apply -f k8s/scheduler-cronjob.yml
```

## Operação

```bash
# Estado geral
kubectl -n kleros get pods,svc,pvc,cronjob

# Logs (LOG_CHANNEL=stderr — o log do Laravel sai aqui)
kubectl -n kleros logs deploy/kleros-main --tail=200
kubectl -n kleros logs deploy/kleros-worker --tail=200
kubectl -n kleros logs -l app=kleros-scheduler --tail=50

# Artisan (tinker, comandos pontuais)
kubectl -n kleros exec deploy/kleros-main -- php artisan about
kubectl -n kleros exec deploy/kleros-main -- php artisan schedule:list

# MySQL
kubectl -n kleros exec deploy/kleros-db -- \
  mysql -u root -p"$ROOT_PW" -e 'SELECT COUNT(*) FROM kleros_db.membros;'

# Rollback do último deploy
kubectl -n kleros rollout undo deploy/kleros-main
kubectl -n kleros rollout undo deploy/kleros-worker
```

## Backup / restore

`php artisan db:backup` (agendado 06:00) roda `mysqldump` contra `kleros-db` e
grava em `storage/backups/database`, que é o PVC `kleros-storage` (subPath
`backups`) — sobrevive a restart de pod. Retém os 2 dumps mais recentes.

```bash
# Backup manual
kubectl -n kleros exec deploy/kleros-main -- php artisan db:backup

# Puxar um dump para a máquina local
kubectl -n kleros exec deploy/kleros-main -- ls -lh storage/backups/database
kubectl -n kleros cp kleros/<pod>:/var/www/html/storage/backups/database/<arquivo>.sql ./restore.sql

# Restaurar
kubectl -n kleros exec -i deploy/kleros-db -- \
  mysql -u root -p"$ROOT_PW" kleros_db < ./restore.sql
```

## Notas

- **O node tem 2 vCPUs e ~97% deles já reservados** pelos outros namespaces
  (`duma`, `globusdei`, `kleroshub`, `talanta`) — o uso real é ~45%. Por isso os
  `requests` de CPU aqui são baixos (main 100m, db 150m, worker 50m); o burst
  fica por conta dos `limits`. Se um pod ficar `Pending` com
  `Insufficient cpu`, é esse teto — reduza requests, não aumente.

- **1 réplica em `kleros-main`**: o PVC de uploads é `local-path`/RWO. Escalar
  exige mover o disco `public` para S3 (já configurado em `config/filesystems.php`).
- **`config:cache` roda no entrypoint**: mudar o ConfigMap exige
  `kubectl -n kleros rollout restart deploy/kleros-main`.
- **Multi-tenant**: o nginx do container é `default_server` e aceita qualquer
  Host; o nginx do host precisa enviar `proxy_set_header Host $host` para o
  wildcard `*.kleros.app` resolver a congregação certa.

Runbook completo de migração/cutover: `docs/DEPLOY_K8S.md`.
