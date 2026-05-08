FROM php:8.2-apache

# HARD RESET all MPM modules (this is the critical part)
RUN rm -f /etc/apache2/mods-enabled/mpm_* \
 && rm -f /etc/apache2/mods-available/mpm_* || true

# Reinstall Apache base modules cleanly
RUN apt-get update \
 && apt-get install -y apache2 \
 && a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2enmod mpm_prefork

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]