FROM php:7.4-apache
COPY . /var/www/html/sheiley-shop-api

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git \
    zip \
    unzip
#Install PHP Extensions
RUN docker-php-ext-install pdo_mysql

#Install Composer
RUN curl https://getcomposer.org/download/1.10.26/composer.phar --output composer.phar
RUN chmod a+x composer.phar
RUN mv composer.phar /usr/local/bin/composer

RUN a2enmod rewrite

EXPOSE 80

WORKDIR /var/www/html/sheiley-shop-api
RUN composer install