FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2enmod mpm_prefork

RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
 && rm -f /etc/apache2/mods-enabled/mpm_worker.load || true

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
