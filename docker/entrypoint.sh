#!/bin/sh
# =============================================================================
# Kleros — entrypoint do container web.
# Roda apenas no kleros-main; worker/scheduler sobrescrevem o command no k8s.
# =============================================================================
set -e

cd /var/www/html

# O PVC é montado em storage/app — as subpastas podem não existir no volume novo
mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/backups/database \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs
chown -R www-data:www-data storage bootstrap/cache

# public/storage -> storage/app/public (o symlink não sobrevive ao build)
php artisan storage:link --force --quiet || true

# Cache de config/rotas/views. Fica no entrypoint (e não no build) porque
# config:cache congela os valores de env — que só existem em runtime.
# Nada aqui pode depender do banco: o pod precisa subir mesmo com o MySQL
# indisponível (por isso não usamos `optimize:clear`, que roda cache:clear).
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[entrypoint] Kleros pronto — iniciando: $*"
exec "$@"
