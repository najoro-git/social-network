FROM php:8.3-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer les dépendances système + support JPEG/PNG/GIF
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libwebp-dev \
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-configure gd \
      --with-freetype \
      --with-jpeg \
      --with-webp \
  && docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    gd \
  && pecl install redis \
  && docker-php-ext-enable redis

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Config Apache : autoriser .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# Dossier de travail
WORKDIR /var/www/html

# DocumentRoot → public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf