#!/bin/sh

# Passer les variables d'env à PHP-FPM
echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Démarrer Nginx au premier plan
nginx -g "daemon off;"