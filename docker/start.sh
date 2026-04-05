#!/bin/sh

# PORT Railway (défaut 80)
export PORT=${PORT:-80}

# Passer les variables d'env à PHP-FPM
echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Attendre que PHP-FPM soit prêt
sleep 2

# Créer la config Nginx avec le bon port
cat > /etc/nginx/http.d/default.conf << EOF
server {
    listen ${PORT};
    server_name _;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    client_max_body_size 10M;
}
EOF

# Démarrer Nginx au premier plan
nginx -g "daemon off;"