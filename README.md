# apiRestJefriMartinez

# Creación de tablas de mi base datos y llenado.
php artisan migrate:fresh --seed
# Creación de tablas de mi base datos de testing y llenado. 
php artisan migrate:fresh --seed --env=testing
# Ejecución de pruebas 
php artisan test