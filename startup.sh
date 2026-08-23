#!/bin/bash

APP_DIR="/home/site/wwwroot"
DATA_DIR="/home/data"

cd "$APP_DIR" || exit 1

echo "== Apuntando Nginx a /public =="
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|' /etc/nginx/sites-available/default
service nginx reload || nginx -s reload

echo "== Configurando almacenamiento persistente =="
mkdir -p "$DATA_DIR/app_public"
mkdir -p "$DATA_DIR/db"

if [ ! -f "$DATA_DIR/db/database.sqlite" ]; then
    touch "$DATA_DIR/db/database.sqlite"
fi

# aseguramos que exista la carpeta padre antes de crear el symlink
mkdir -p storage/app
rm -rf storage/app/public
ln -s "$DATA_DIR/app_public" storage/app/public

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

echo "== Corriendo migraciones =="
php artisan migrate --force

echo "== Cacheando configuración =="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "== Listo =="