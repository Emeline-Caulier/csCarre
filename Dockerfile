FROM php:8.2-apache

# Installation des dépendances PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql

# Point d'entrée Apache
RUN echo "DirectoryIndex index_.php index.php index.html" \
    > /etc/apache2/conf-available/directoryindex.conf \
    && a2enconf directoryindex

# Copie du code
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
