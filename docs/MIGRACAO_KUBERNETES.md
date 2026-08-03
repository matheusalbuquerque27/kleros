# Migração do Kleros para Kubernetes — registro

**Data:** 3 de agosto de 2026
**Downtime:** 1 min 57 s (17:57:35 → 17:59:32, horário do servidor)
**Resultado:** produção rodando em containers no cluster k3s, namespace `kleros`

Este documento registra **como o sistema estava rodando**, **como passou a
rodar** e **o que foi feito** para migrar. Para operar o ambiente novo, ver
`k8s/README.md`; para a arquitetura e o runbook, `DEPLOY_K8S.md`.

---

## 1. Como estava antes (bare-metal)

Tudo rodava direto no sistema operacional do VPS Hostinger `72.61.60.208`
(`srv1158721`, Ubuntu 22.04, 2 vCPU, 8 GB RAM) — o **mesmo** servidor que já
hospedava o cluster k3s de outros projetos.

```
  Internet
     │  kleros.app, *.kleros.app, domínios de clientes
     ▼
  nginx do host  ── TLS (Let's Encrypt wildcard kleros.app-0001)
     │  fastcgi_pass unix:/run/php/php8.2-fpm.sock
     ▼
  php-fpm 8.2 (host)  →  /var/www/kleros/public
     │
     ├── MySQL 8.0.46 do host (127.0.0.1:3306, db kleros_db, user kleros_user)
     ├── /var/www/kleros/storage/app/public   (uploads, 16 MB)
     ├── /var/www/kleros/storage/backups      (1,9 GB acumulados)
     │
     ├── crontab do root:  * * * * * cd /var/www/kleros && php artisan schedule:run
     └── processo solto:   php artisan queue:work database --sleep=3 --tries=3 --timeout=90
```

**Componentes e onde viviam:**

| Parte | Como rodava |
|---|---|
| Aplicação (Laravel 11, PHP 8.2) | Código em `/var/www/kleros`, servido por php-fpm do host |
| Servidor web / TLS | nginx do host, **4 server blocks**: `kleros.app`, `*.kleros.app` e dois catch-all `default_server` (domínios personalizados de clientes) |
| Banco | MySQL 8.0 instalado no host, compartilhado com o ambiente `klerostest` |
| Sessão, cache e fila | Todos no driver `database` (sem Redis) |
| Uploads | Disco `public` → `storage/app/public`, symlink `public/storage` |
| Fila | `queue:work` como processo solto, sem supervisão |
| Agendamento | `schedule:run` no crontab do root |
| Assets | `npm run build` executado **no servidor de produção** |
| Deploy | `./deploy.sh` ou GitHub Actions via SSH: `php artisan down` → `git pull` → `composer install` → `npm run build` → `migrate` → caches → `up` |
| Rollback | Manual, `git reset --hard <commit>` + reinstalar dependências |
| PDF | `LARAVEL_PDF_DRIVER=dompdf` (Chrome/Browsershot instalado mas não usado) |

**Problemas desse arranjo:** build rodando no servidor de produção, artefato de
deploy não reprodutível (o que estava em `vendor/` e `public/build` não vinha de
lugar nenhum versionado), rollback manual e demorado, aplicação sem limite de
recursos competindo com o k3s, e `php artisan down` a cada deploy.

---

## 2. Como ficou (k3s)

```
  Internet
     │  kleros.app, *.kleros.app, domínios de clientes
     ▼
  nginx do host  ── TLS (mesmo certificado wildcard)
     │  proxy_pass http://127.0.0.1:30311
     │  Host + X-Forwarded-* preservados · SEM keepalive no upstream
     ▼
  Service kleros-main (NodePort 30311)
     ▼
  ┌────────────────────────────────────────────────────────────────┐
  │ namespace: kleros                        node srv1158721       │
  │                                                                │
  │  kleros-main       Deployment · nginx + php-fpm (supervisord)  │
  │    └ initContainer   php artisan migrate --force               │
  │  kleros-worker     Deployment · queue:work database            │
  │  kleros-scheduler  CronJob */1 · schedule:run                  │
  │  kleros-db         Deployment · mysql:8.0 · ClusterIP :3306    │
  │                                                                │
  │  PVC kleros-storage  5Gi  → storage/app + storage/backups      │
  │  PVC kleros-db-data 10Gi  → /var/lib/mysql                     │
  └────────────────────────────────────────────────────────────────┘

  Imagem única: ghcr.io/matheusffalbuquerque/kleros:<sha>
  (worker, scheduler e initContainer só trocam o `command`)
```

### O que mudou, parte por parte

| Parte | Antes | Depois |
|---|---|---|
| Código da aplicação | `/var/www/kleros` (mutável, `git pull`) | Dentro da imagem, imutável, tagueada pelo SHA do commit |
| PHP | php-fpm 8.2 do host | php-fpm 8.2 no container, mesmas extensões |
| Servidor web | nginx do host servindo arquivos | nginx **dentro** do container (`default_server`, aceita qualquer Host); o nginx do host virou só terminador TLS + proxy |
| Banco | MySQL do host | Pod `kleros-db` (mysql:8.0) com PVC, acessível só dentro do cluster |
| Uploads | `storage/app/public` no disco do host | PVC `kleros-storage`, montado em `storage/app` (subPath `app`) |
| Backups do banco | `storage/backups` no disco do host | Mesmo PVC, subPath `backups` — sobrevive a restart de pod |
| Fila | Processo solto (morria sem supervisão) | Deployment `kleros-worker`, reinício automático |
| Agendamento | crontab do root | CronJob `kleros-scheduler`, `concurrencyPolicy: Forbid` |
| Assets | Compilados no servidor | Compilados no build da imagem (stage `assets`) |
| Logs | `storage/logs/laravel.log` no disco | `LOG_CHANNEL=stderr` → `kubectl logs` |
| Configuração | `.env` no servidor | ConfigMap `kleros-config` + Secret `kleros-secrets` |
| Deploy | SSH + git pull + build, com downtime | Push na `main` → imagem no GHCR → rolling update **sem downtime** |
| Rollback | `git reset` + reinstalar | `kubectl rollout undo` |
| Limites de recurso | Nenhum | requests/limits por workload |

### O que **não** mudou

- Domínios, certificados e o nginx do host como terminador TLS.
- Drivers de sessão, cache e fila (`database`) — não foi introduzido Redis.
- `dompdf` como driver de PDF; a imagem **não** tem Chrome (economiza ~400 MB).
- A lógica multi-tenant por domínio (`AcessarCongregacaoPeloDominio`).
- O `APP_KEY` — foi reaproveitado o mesmo, senão sessões e dados encriptados
  existentes quebrariam.

### Alterações no código da aplicação

Foram só duas, ambas obrigatórias para o modo container:

1. **`bootstrap/app.php` — `trustProxies`.** Antes o nginx falava com o PHP por
   FastCGI e informava `HTTPS=on`. Atrás de `proxy_pass`, o Laravel passaria a
   enxergar `http`, gerando URLs erradas e quebrando o `SESSION_SECURE_COOKIE=true`.
2. **`DatabaseBackupCommand`** — o destino `/var/www/klerostest` estava fixo no
   código e não existe no container. Virou `BACKUP_TEST_PATH` (vazio = não copia).

---

## 3. Como a migração foi executada

### Fase 0 — Preparação
Namespace `kleros` criado, `ghcr-credentials` copiado do namespace `kleroshub`,
e o Secret `kleros-secrets` gerado lendo os valores direto de
`/var/www/kleros/.env` (o `MYSQL_ROOT_PASSWORD` foi gerado novo, já que não
existia antes).

### Fase 1 — Ambiente em paralelo
Imagem construída e testada localmente, exportada com `docker save` e importada
no containerd do servidor (`k3s ctr images import`) — evitando build no VPS, que
tem pouca RAM e disco. Os quatro workloads subiram e o `initContainer` criou as
**104 tabelas**. Produção seguiu intocada o tempo todo.

### Fase 2 — Migração dos dados
`mysqldump --single-transaction` (leitura sem lock) da produção → import no pod
`kleros-db`. Uploads copiados para o PVC com `uid:gid 33` (www-data).

### Fase 3 — Validação por túnel SSH
Sem expor o ambiente publicamente: túnel `127.0.0.1:8311 → NodePort 30311` e
bateria de testes. Login por congregação, assets, uploads do PVC, PWA, sessão +
CSRF, PDF e processamento de fila ponta a ponta.

### Fase 4 — Cutover
1. Backup do vhost em `kleros.pre-k8s.bak`
2. `php artisan down` no app antigo — **início do downtime, 17:57:35**
3. Dump final (23 MB) e import no cluster
4. `rsync` do delta de uploads; `migrate --force` → *Nothing to migrate*
5. Vhost trocado pelos 4 server blocks em modo proxy, `nginx -t` e reload — **fim do downtime, 17:59:32**
6. Crontab do host limpo, `queue:work` antigo encerrado, CronJob do k8s reativado

### Fase 5 — CI/CD
Secrets configurados no GitHub, commit e push na `main`. O workflow construiu a
imagem, publicou no GHCR e fez o rolling update — validando o ciclo completo.

---

## 4. Problemas encontrados e como foram resolvidos

| Problema | Causa | Solução |
|---|---|---|
| `composer install` falhava no build | A imagem `composer:2` hoje roda PHP 8.5; o `composer.lock` exige 8.1–8.4 | Rodar o composer sobre a **mesma base** `php:8.2-fpm` do runtime |
| Extensões `gd` e `zip` não carregavam | `apt purge --auto-remove` dos pacotes `-dev` levou junto `libpng16-16` e `libzip4` | Reinstalar só as libs de runtime; build falha se `php -m` não listar as extensões |
| Pod entrava em crashloop sem o banco | `optimize:clear` chama `cache:clear`, que precisa do MySQL | Entrypoint faz só `config/route/view:cache` — o pod sobe mesmo com o banco fora |
| `kleros-main` ficava `Pending` | Node tem **2 vCPUs** e ~97% já reservados pelos outros namespaces (uso real: 44%) | Requests dimensionados pelo consumo medido (main: 25m CPU / 256Mi) |
| Deploy derrubava ~3% dos requests | `keepalive` no upstream do nginx: conexões TCP ficavam presas no pod antigo e estouravam quando ele morria | Remover o `keepalive`. Em loopback o ganho é irrelevante |
| Risco de e-mails duplicados | CronJob do k8s rodando junto com o crontab do host | CronJob suspenso durante o paralelo; reativado só no cutover, junto com a remoção do crontab |

O ponto mais fácil de errar foi o vhost: ele tem **4 server blocks** servindo a
aplicação, não 2. Os dois catch-all `default_server` atendem os domínios
personalizados de clientes — converter só `kleros.app` e o wildcard teria
deixado esses clientes apontando para um app em manutenção.

---

## 5. Verificação

**Integridade dos dados** (produção → cluster, contagens idênticas):

| Tabela | Origem | Destino |
|---|---|---|
| membros | 201 | 201 |
| congregacoes | 10 | 10 |
| dominios | 10 | 10 |
| users | 19 | 19 |
| migrations | 87 | 87 |

**Funcional, por HTTPS público real:** `kleros.app` e `/up` em 200; `/login` de
cada congregação renderizando o nome e o tema certos (AD Jerusalém, AH Ilha,
IBC); logo vindo do PVC em 200 `image/png`; assets do Vite em 200; redirect
HTTP→HTTPS em 301; cookie `Secure; HttpOnly; SameSite=Lax; domain=.kleros.app`
aceito e sessão persistindo entre requests (driver `database`); PDF gerado pelo
dompdf dentro do container; job despachado e processado pelo worker em 1 s.

**Deploy sem downtime**, com carga contínua durante o rollout:

| Configuração | Requests | Falhas |
|---|---|---|
| `RollingUpdate` com keepalive no upstream | 90 | 3 (502, conexão recusada) |
| Acrescentando `preStop` | 106 | 4 |
| **Sem keepalive** | 114 | **0** |
| **Confirmação** | 131 | **0** |

---

## 6. Rollback

Enquanto o ambiente antigo for mantido:

```bash
cp /etc/nginx/sites-available/kleros.pre-k8s.bak /etc/nginx/sites-available/kleros
nginx -t && systemctl reload nginx
cd /var/www/kleros && php artisan up
crontab /root/crontab.pre-k8s.bak
```

`/var/www/kleros` e o MySQL do host seguem intactos, em modo manutenção. Dados
gravados no cluster após o cutover **não** voltam sozinhos.

Para reverter apenas uma release: `kubectl -n kleros rollout undo deploy/kleros-main`.

---

## 7. O que ficou pendente

- **Aposentar o bare-metal** (sugerido: após ~1 semana de operação estável).
  Libera ~2 GB de `storage/backups` e o banco antigo — o disco está em 82%.
- **`/phpmyadmin`** continua apontando para o MySQL do host, que virou cópia
  morta. Repontar para o pod `kleros-db` ou remover do vhost, para ninguém
  editar dados achando que mexe no banco vivo.
- **`kleros-main` está preso em 1 réplica**: o PVC de uploads é `local-path`/RWO.
  Escalar exige mover o disco `public` para S3 (já previsto em
  `config/filesystems.php`).
- **`KUBE_CONFIG` do CI é cluster-admin** do cluster inteiro, alcançando os
  namespaces `duma`, `globusdei` e `talanta`. Dá para restringir com um
  ServiceAccount limitado ao namespace `kleros`.
- **`klerostest`** segue no modelo antigo (`deploy.sh`, `git pull`), fora do
  escopo desta migração.
