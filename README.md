# apiRestJefriMartinez

# Creación de tablas de mi base datos y llenado.
php artisan migrate:fresh --seed
# Creación de tablas de mi base datos de testing y llenado. 
php artisan migrate:fresh --seed --env=testing
# Ejecución de pruebas 
php artisan test

Autentication
curl -X POST http://127.0.0.1:7000/api/logout -H 'Accept: application/json'
curl -X POST http://127.0.0.1:7000/api/login -F "email=test@example.com" -F "password=0713"

/products index
curl -X GET http://127.0.0.1:7000/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

/products store
curl -X POST http://127.0.0.1:7000/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" -F "name=Producto 01" -F "sku=2342323WEWEWE" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

/products/id show
curl -X GET http://127.0.0.1:7000/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

/products/id destroy
curl -X DELETE http://127.0.0.1:7000/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

/products/id update
curl -X POST http://127.0.0.1:7000/api/products/{id} -H "Accept: application/json" -H "Authorization: Bearer 5|laravel_sanctum_qMAtLDZquDUWkIaagkG0Rj1i2YCy8WuR3c1xyc7W06ba7a54" -H "Content-Type: application/json" -F "name=Producto 02" -F "sku=2342323WE23E" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

curl -X POST http://127.0.0.1:7000/api/products/{id} -H "Accept: application/json" -H "Authorization: Bearer 5|laravel_sanctum_qMAtLDZquDUWkIaagkG0Rj1i2YCy8WuR3c1xyc7W06ba7a54" -H "Content-Type: application/json" -d "{"name":"Producto 02","sku":"2342323WE23E","description":"Producto 01 test","price":3000,"iva":19,"active":1}"

