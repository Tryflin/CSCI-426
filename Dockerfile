FROM php:8.2-apache


RUN rm -rf /etc/apache2/mods-enabled/* \
 && rm -rf /etc/apache2/mods-available/mpm_* || true

RUN apt-get update \
 && apt-get install -y apache2 \
 && a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2enmod mpm_prefork

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
