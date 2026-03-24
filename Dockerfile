FROM php:8.2-apache

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# Mantiene /php/ nel path — tutti i link del codice funzionano
COPY ./php /var/www/html/php
COPY ./public /var/www/html/public

# Redirect dalla root a /public/index.php
RUN echo '<meta http-equiv="refresh" content="0;url=/public/index.php">' \
    > /var/www/html/index.html