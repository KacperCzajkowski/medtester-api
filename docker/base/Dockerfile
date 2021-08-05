FROM php:8.0.3-apache-buster AS med-tester-api-base

WORKDIR /var/www/med-tester-api/

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y git unzip libicu-dev libpq-dev libzip-dev zip && docker-php-ext-install zip

RUN docker-php-ext-install pdo pdo_pgsql

RUN docker-php-ext-install \
    opcache \
    intl

COPY --from=composer:2.0.12 /usr/bin/composer /usr/bin/composer