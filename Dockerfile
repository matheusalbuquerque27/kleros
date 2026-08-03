# =============================================================================
# Kleros — imagem de produção (nginx + php-fpm 8.2 via supervisord)
# A mesma imagem serve kleros-main (web), kleros-worker (queue) e o CronJob
# kleros-scheduler — o que muda é o command no manifest k8s.
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 0 — base PHP 8.2 com as extensões de produção
# Compartilhada pelo stage de vendor e pelo runtime, para que o composer resolva
# as dependências contra exatamente a mesma plataforma que vai rodar a app.
# Extensões espelham o `php -m` do servidor atual.
# -----------------------------------------------------------------------------
FROM php:8.2-fpm-bookworm AS base

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libzip-dev \
        libonig-dev \
        unzip \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        gd \
        intl \
        zip \
        bcmath \
        pcntl \
        opcache \
    ; \
    # Descarta os headers -dev e reinstala só as libs compartilhadas de que as
    # extensões precisam em runtime (~59 MB a menos na imagem).
    apt-get purge -y --auto-remove \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libzip-dev \
        libonig-dev \
    ; \
    apt-get install -y --no-install-recommends \
        libpng16-16 \
        libjpeg62-turbo \
        libfreetype6 \
        libzip4 \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    # Falha o build se alguma extensão não carregar
    php -m | grep -qx gd; \
    php -m | grep -qx zip; \
    php -m | grep -qx intl; \
    php -m | grep -qx pdo_mysql

# -----------------------------------------------------------------------------
# Stage 1 — dependências PHP
# -----------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Camada cacheável: só invalida quando composer.json/lock mudam
COPY composer.json composer.lock ./
RUN composer install \
      --no-dev \
      --no-scripts \
      --no-autoloader \
      --prefer-dist \
      --no-interaction

COPY . .

# package:discover roda no stage de runtime (precisa das extensões PHP finais)
RUN composer dump-autoload --no-dev --optimize --no-scripts

# -----------------------------------------------------------------------------
# Stage 2 — assets (Vite + Tailwind + Sass)
# -----------------------------------------------------------------------------
FROM node:20-alpine AS assets

WORKDIR /app

# Produção usa dompdf (LARAVEL_PDF_DRIVER), não Browsershot — não baixar Chromium
ENV PUPPETEER_SKIP_DOWNLOAD=true

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources

# O content do tailwind.config.js inclui as views de paginação do framework
COPY --from=vendor /app/vendor/laravel/framework ./vendor/laravel/framework

RUN npm run build

# -----------------------------------------------------------------------------
# Stage 3 — runtime
# -----------------------------------------------------------------------------
FROM base AS runtime

ENV APP_ENV=production \
    APP_DEBUG=false

# default-mysql-client é necessário para o `php artisan db:backup` (mysqldump).
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        default-mysql-client \
    ; \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Configurações de nginx / php / php-fpm / supervisord
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-kleros.ini
COPY docker/www.conf /usr/local/etc/php-fpm.d/zz-kleros.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Código da aplicação + dependências + assets compilados
COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Equivalente ao post-autoload-dump (pulado no stage 1)
RUN php artisan package:discover --ansi

RUN set -eux; \
    mkdir -p \
        storage/app/public \
        storage/backups/database \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    rm -rf /var/www/html/tests /var/www/html/docs

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
