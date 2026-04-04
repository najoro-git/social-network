FROM php:8.3-apache

# Fixer le problème MPM — désactiver tout puis activer prefork uniquement
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libwebp-dev \
    libssl-dev \
    zip unzip git curl \
  && docker-php-ext-configure gd \
      --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install pdo pdo_mysql zip gd \
  && pecl install redis \
  && docker-php-ext-enable redis

# Désactiver les MPM en conflit
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
  && a2enmod mpm_prefork \
  && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

COPY src/ /var/www/html/

RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]