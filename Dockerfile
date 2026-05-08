FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
