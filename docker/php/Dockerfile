# Stage 1: Build PHP extensions
FROM php:8.4-fpm-trixie AS builder

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libicu-dev \
       libzip-dev \
    && docker-php-ext-install -j"$(nproc)" \
       pdo_mysql \
       bcmath \
       intl \
       zip \
       opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Stage 2: Runtime
FROM php:8.4-fpm-trixie AS runtime

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libicu76 \
       libzip5 \
       unzip \
       git \
    && rm -rf /var/lib/apt/lists/*

COPY --from=builder /usr/local/lib/php/extensions/ \
    /usr/local/lib/php/extensions/

COPY --from=builder /usr/local/etc/php/conf.d/ \
    /usr/local/etc/php/conf.d/

COPY --from=composer:2 /usr/bin/composer \
    /usr/local/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]
