#!/bin/bash

set -e

APP_DIR="/home/site/wwwroot"
DATA_DIR="/home/data"
NGINX_CONFIG="/etc/nginx/sites-enabled/default"

echo "========================================"
echo " Iniciando Portfolio Laravel"
echo "========================================"

cd "$APP_DIR" || exit 1

echo "== Configurando Nginx =="

# Laravel debe servirse desde /public
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' \
    /etc/nginx/sites-enabled/default \
    /etc/nginx/sites-available/default 2>/dev/null || true

# Configuración Laravel:
# Todas las rutas pasan por index.php si no existe un archivo físico.
sed -i '/location \/ {/,/}/c\
    location / {\
        try_files $uri $uri/ /index.php?$query_string;\
        index index.php index.html index.htm;\
    }' "$NGINX_CONFIG"

echo "== Verificando Nginx =="

nginx -t

echo "== Recargando Nginx =="

nginx -s reload || service nginx reload || true


echo "== Configurando almacenamiento persistente =="

mkdir -p "$DATA_DIR/app_public"
mkdir -p "$DATA_DIR/db"

# Crear SQLite solamente si todavía no existe.
if [ ! -f "$DATA_DIR/db/database.sqlite" ]; then
    echo "Creando database.sqlite..."
    touch "$DATA_DIR/db/database.sqlite"
fi


echo "== Configurando storage persistente =="

mkdir -p "$APP_DIR/storage/app"

rm -rf "$APP_DIR/storage/app/public"

ln -s "$DATA_DIR/app_public" "$APP_DIR/storage/app/public"


echo "== Creando enlace public/storage =="

if [ -L "$APP_DIR/public/storage" ] || [ -e "$APP_DIR/public/storage" ]; then
    rm -rf "$APP_DIR/public/storage"
fi

php artisan storage:link || true


echo "== Ejecutando migraciones =="

php artisan migrate --force


echo "== Limpiando cachés anteriores =="

php artisan optimize:clear


echo "== Cacheando configuración =="

php artisan config:cache


echo "== Cacheando rutas =="

php artisan route:cache


echo "== Cacheando vistas =="

php artisan view:cache


echo "========================================"
echo " Laravel iniciado correctamente"
echo "========================================"