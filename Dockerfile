FROM php:7.4-apache

## Update package information
RUN apt-get update

## Configure Apache
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
    && mv /var/www/html /var/www/public

## Install Composer
RUN curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

## Install zip libraries and extension
RUN apt-get install --yes git zlib1g-dev libzip-dev \
    && docker-php-ext-install zip

## Install intl library and extension
RUN apt-get install --yes libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

## PostgreSQL PDO support
RUN apt-get install --yes libpq-dev \
 && docker-php-ext-install pdo_pgsql

## Memcached
RUN apt-get install --yes libmemcached-dev \
 && pecl install memcached \
 && docker-php-ext-enable memcached

WORKDIR /var/www
