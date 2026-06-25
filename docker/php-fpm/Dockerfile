FROM php:8.4-fpm-alpine

ARG UID
ARG GID

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk --update add libpng-dev libjpeg-turbo-dev openssl-dev zlib-dev curl \
    $PHPIZE_DEPS \
    git unzip libxml2-dev libpq-dev libzip-dev icu-dev linux-headers && \
    pecl install apcu && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install -j$(nproc) zip opcache gd intl pdo pdo_mysql && \
    docker-php-ext-enable apcu sodium && \
    rm -rf /tmp/* /var/tmp/*

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

USER ${UID}:${GID}
