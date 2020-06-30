FROM php:7.2-apache
COPY . /var/www/html/sheyley-shop-api

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git \
    zip \
    unzip
#Install PHP Extensions
RUN docker-php-ext-install pdo_mysql

#Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite

EXPOSE 80

WORKDIR /var/www/html/sheyley-shop-api
RUN composer install