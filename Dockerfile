FROM php:8.1-fpm

WORKDIR /var/www

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions \
    zip \
    bcmath \
    pgsql \
    pdo_pgsql \
    redis \
    opcache \
    imagick

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    curl \
    nginx \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    mkdir /var/log/php && \
    touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

COPY composer* ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --apcu-autoloader \
    --ansi \
    --no-scripts

COPY .docker/php.ini /usr/local/etc/php/conf.d/php.ini
COPY .docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY .docker/nginx.conf /etc/nginx/sites-enabled/default
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY --chown=www:www-data . .

RUN chmod +x .docker/entrypoint.sh
RUN cat .docker/utilities.sh >> ~/.bashrc
RUN chmod -R ug+w /var/www/storage

EXPOSE 80

ENTRYPOINT [".docker/entrypoint.sh"]

HEALTHCHECK --start-period=8s --interval=60s --timeout=5s CMD curl http://localhost/healthCheck || exit 1
