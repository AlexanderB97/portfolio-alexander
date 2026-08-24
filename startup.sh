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
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|' "$NGINX_CONFIG"
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|' /etc/nginx/sites-available/default 2>/dev/null || true

# Configuración correcta para Laravel:
# Las rutas que no sean archivos físicos pasan a index.php.
python3 - <<'PY'
from pathlib import Path

paths = [
    Path("/etc/nginx/sites-enabled/default"),
    Path("/etc/nginx/sites-available/default"),
]

for path in paths:
    if not path.exists():
        continue

    text = path.read_text()

    old = """    location / {
        index index.php index.html index.htm hostingstart.html;
    }"""

    new = """    location / {
        try_files $uri $uri/ /index.php?$query_string;
        index index.php index.html index.htm;
    }"""

    if old in text:
        text = text.replace(old, new)

    path.write_text(text)
PY

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