FROM php:8.2-apache

# Remove all enabled MPMs first
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2dismod mpm_prefork || true

# Enable only prefork
RUN a2enmod mpm_prefork

# Install MySQL PDO extension
RUN docker-php-ext-install pdo pdo_mysql

# Copy app files
COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]