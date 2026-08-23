#!/bin/bash
set -e

APP_DIR="/home/site/wwwroot"
DATA_DIR="/home/data"

echo "== Configurando almacenamiento persistente =="
mkdir -p "$DATA_DIR/app_public"
mkdir -p "$DATA_DIR/db"

if [ ! -f "$DATA_DIR/db/database.sqlite" ]; then
    touch "$DATA_DIR/db/database.sqlite"
fi

cd "$APP_DIR"

# storage/app/public apunta a la carpeta persistente en vez de vivir dentro
# del código que se reemplaza en cada deploy
rm -rf storage/app/public
ln -s "$DATA_DIR/app_public" storage/app/public

# public/storage -> storage/app/public (que a su vez apunta a /home/data/app_public)
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

echo "== Corriendo migraciones =="
php artisan migrate --force

echo "== Cacheando configuración =="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "== Apuntando Apache a /public =="
sed -i 's!/home/site/wwwroot!/home/site/wwwroot/public!g' /etc/apache2/sites-available/000-default.conf
sed -i 's!/home/site/wwwroot!/home/site/wwwroot/public!g' /etc/apache2/apache2.conf
service apache2 reload

echo "== Listo =="