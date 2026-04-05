#!/bin/sh

# PORT Railway (défaut 80)
PORT=${PORT:-80}

# Passer les variables d'env à PHP-FPM
echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Remplacer le port dans la config nginx
sed -i "s/NGINX_PORT/$PORT/" /etc/nginx/http.d/default.conf

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Attendre PHP-FPM
sleep 2

# Tester la config nginx
nginx -t

# Démarrer Nginx
exec nginx -g "daemon off;"