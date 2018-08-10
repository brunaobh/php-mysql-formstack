FROM php:7.2.8-apache

RUN apt-get update && apt-get install -y \
    libmcrypt-dev libicu-dev libxml2-dev mysql-client \
    && docker-php-ext-install pdo_mysql mbstring intl xml json \
    && a2enmod rewrite \
    && service apache2 restart
