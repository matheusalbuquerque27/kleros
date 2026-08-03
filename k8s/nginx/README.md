# nginx do host (fora do cluster)

Cópia versionada da configuração que está em produção no VPS. **Estes arquivos
não são aplicados por nenhum workflow** — servem de referência e de base para
restaurar em caso de perda do servidor.

| Arquivo aqui | Caminho no VPS |
|---|---|
| `kleros.vhost.conf` | `/etc/nginx/sites-available/kleros` (symlink em `sites-enabled/`) |
| `kleros-proxy.snippet.conf` | `/etc/nginx/snippets/kleros-proxy.conf` |

O vhost tem **4 server blocks** que servem a aplicação — esquecer qualquer um
deixa parte dos clientes fora do ar:

1. `kleros.app` / `www.kleros.app` (443)
2. `*.kleros.app` (443) — subdomínios de congregação
3. `_` (80, `default_server`) — domínios personalizados de clientes
4. `_` (443, `default_server`) — idem, com TLS

Todos apontam para `upstream kleros_k8s` → `127.0.0.1:30311` (NodePort do
Service `kleros-main`).

## Detalhes que não podem mudar

- **`proxy_set_header Host $host`** — é por ele que `AcessarCongregacaoPeloDominio`
  resolve a congregação. Sem isso o multi-tenant quebra inteiro.
- **`X-Forwarded-Proto $scheme`** — o Laravel confia nele (`trustProxies` em
  `bootstrap/app.php`); sem isso o `SESSION_SECURE_COOKIE=true` impede o login.
- **Sem `keepalive` no `upstream`** — com keepalive, todo deploy derrubava ~3%
  dos requests. Ver `docs/DEPLOY_K8S.md`.
- `client_max_body_size 100M` em todos os blocks, igual ao php.ini da imagem.

## Backup do estado anterior (bare-metal)

No VPS, o vhost que servia `/var/www/kleros` via php-fpm está preservado em
`/etc/nginx/sites-available/kleros.pre-k8s.bak`, e o crontab antigo em
`/root/crontab.pre-k8s.bak`. Procedimento de rollback em `docs/DEPLOY_K8S.md`.
