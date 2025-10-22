ARG USER_ID=82
ARG GROUP_ID=82

FROM serversideup/php:8.4-fpm-nginx-alpine AS base

USER root

ARG USER_ID
ARG GROUP_ID

RUN install-php-extensions bcmath gd exif intl

RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx

WORKDIR /var/www/html
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

USER www-data

FROM node:24-alpine AS static-assets

WORKDIR /app
COPY package*.json vite.config.js ./
COPY --from=base --chown=www-data:www-data /var/www/html/vendor ./vendor
RUN npm ci
COPY . .
RUN npm run build


FROM serversideup/php:8.4-fpm-nginx-alpine

WORKDIR /var/www/html

USER root

ARG USER_ID
ARG GROUP_ID

RUN install-php-extensions bcmath gd exif intl

RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx

# Install PostgreSQL repository and keys
RUN apk add --no-cache gnupg && \
    mkdir -p /usr/share/keyrings && \
    curl -fSsL https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor > /usr/share/keyrings/postgresql.gpg

# Install system dependencies
RUN apk add --no-cache \
    postgresql17-client
# openssh-client \
# git \
# git-lfs \
# jq \
# lsof \
# vim

# Configure shell aliases
RUN echo "alias ll='ls -al'" >> /etc/profile && \
    echo "alias a='php artisan'" >> /etc/profile && \
    echo "alias logs='tail -f storage/logs/laravel.log'" >> /etc/profile

ENV PHP_OPCACHE_ENABLE=1
ENV SSL_MODE=mixed

COPY --from=base --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=static-assets --chown=www-data:www-data /app/public/build ./public/build

COPY --chown=www-data:www-data composer.json composer.lock ./
COPY --chown=www-data:www-data app ./app
COPY --chown=www-data:www-data bootstrap ./bootstrap
COPY --chown=www-data:www-data config ./config
COPY --chown=www-data:www-data database ./database
COPY --chown=www-data:www-data lang ./lang
COPY --chown=www-data:www-data public ./public
COPY --chown=www-data:www-data routes ./routes
COPY --chown=www-data:www-data storage ./storage
# COPY --chown=www-data:www-data templates ./templates
COPY --chown=www-data:www-data resources ./resources
COPY --chown=www-data:www-data artisan artisan

RUN composer dump-autoload

# RUN mkdir -p /etc/nginx/conf.d && \
#     chown -R www-data:www-data /etc/nginx && \
#     chmod -R 755 /etc/nginx

USER www-data
