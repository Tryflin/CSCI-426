FROM php:8.2-apache

RUN rm -f /etc/apache2/mods-enabled/mpm_* \
 && a2enmod mpm_prefork

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]