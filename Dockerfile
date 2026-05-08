FROM php:8.2-apache

# Fix Apache MPM conflict
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Install MySQL PDO driver
RUN docker-php-ext-install pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

EXPOSE 80