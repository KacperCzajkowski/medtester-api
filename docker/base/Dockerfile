FROM php:8.0.9-apache-buster as medtester-api-base

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install git bash libpq-dev libzip-dev unzip wkhtmltopdf
RUN docker-php-ext-install pdo pdo_pgsql
RUN groupadd --gid 1000 medtester-api \
  && useradd --uid 1000 --gid medtester-api --shell /bin/bash --create-home medtester-api



RUN docker-php-ext-install opcache zip

COPY --from=composer:2.1.5 /usr/bin/composer /usr/bin/composer

WORKDIR /home/medtester-api/project
