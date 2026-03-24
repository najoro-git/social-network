# Recette pour construire un image personnalisée
#image officielle déjà installés.
FROM php:8.3-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer les extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    gd \
  && pecl install redis \
  && docker-php-ext-enable redis

# Installer Composer : gestion de dépendances php depuis une autre image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Config Apache : autoriser .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# Dossier de travail 
WORKDIR /var/www/html

# DocumentRoot → public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf