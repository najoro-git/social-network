#!/bin/sh

PORT=${PORT:-80}

# Config PHP-FPM
echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf
echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf
echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Config Nginx port
sed -i "s|NGINX_PORT|$PORT|g" /etc/nginx/http.d/default.conf

# Démarrer PHP-FPM
php-fpm -D

# Attendre PHP-FPM avec sleep simple
sleep 5

echo "Starting Nginx on port $PORT"

# Démarrer Nginx
nginx -g "daemon off;"