#!/bin/bash
php artisan optimize:clear
php artisan key:generate
sleep 10

# Realiza la migración y si es exitoso, realiza el seed
if [ ! -f /var/www/html/.migrated ]; then
    php artisan migrate:refresh --force
    php artisan db:seed --force
    touch /var/www/html/.migrated  # Crear un archivo de marcador
fi
php artisan l5-swagger:generate
# Ejecutar el servidor de Laravel
echo "Iniciando el servidor de Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
