FROM php:8.2-apache

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

COPY ./php /var/www/html
COPY ./public /var/www/html/public