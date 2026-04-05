#!/bin/sh

# PORT Railway (défaut 80)
PORT=${PORT:-80}

# Forcer PHP-FPM sur port 9000
echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf
echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf
echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Remplacer le port dans la config nginx
sed -i "s|NGINX_PORT|$PORT|" /etc/nginx/http.d/default.conf

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

while ! nc -z 127.0.0.1 9000; do
  echo "Waiting for PHP-FPM "
  sleep 1
done

echo "starting nginx on port $PORT"


# Démarrer
nginx -g "daemon off;"