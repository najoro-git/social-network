FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    libwebp-dev \
    autoconf \
    g++ \
    make \
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
  && docker-php-ext-enable redis \
  && apk del autoconf g++ make

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /run/nginx
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY src/ /var/www/html/

RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html/public

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]