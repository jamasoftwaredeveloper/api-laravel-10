# apiRestJefriMartinez

# Creación de tablas de mi base datos y llenado.
php artisan migrate:fresh --seed
# Creación de tablas de mi base datos de testing y llenado. 
php artisan migrate:fresh --seed --env=testing
# Ejecución de pruebas 
php artisan test

//.........................................DOCUMENTACIÓN API............................................//

Autenticación
POST /register: Registra un nuevo usuario.
POST /login: Inicia sesión a un usuario existente.

POST /v2/logout: Cierra la sesión del usuario actual.
GET /v2/user/profile: Obtiene el perfil del usuario actual.

Contamos con dos versiones.
1. v1 Apis sin auntenticacion.
2. v2 Apis con auntenticacion.

Productos

GET api/{version}/products: Obtiene todos los productos.
GET api/{version}/products/{id}: Obtiene un producto por ID.
POST api/{version}/products: Crea un nuevo producto.
PUT api/{version}/products/{id}: Actualiza un producto.
DELETE /products/{id}: Elimina un producto.
PUT /products/{product}/inactiveOrActivate: Activa o desactiva un producto.
Ventas

GET api/{version}/sales: Obtiene todas las ventas.
GET api/{version}/sales/{id}: Obtiene una venta por ID.
POST api/{version}/sales: Crea una nueva venta.
PUT api/{version}/sales/{id}: Actualiza una venta.
DELETE api/{version}/sales/{id}: Elimina una venta.

//...........................................CURL.................................................//
-------------------------------------------Autentication-----------------------------------------
Nota: 
1. auth_token: lo retorna la petición del login.
2. domain: dominio o url

curl -X POST {domain}/api/login -F "email=test@example.com" -F "password=0713"
curl -X POST {domain}/api/logout -H 'Accept: application/json'


/products index
curl -X GET {domain}/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

/products store
curl -X POST {domain}/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" -F "name=Producto 01" -F "sku=2342323WEWEWE" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

/products/{id} show
curl -X GET {domain}/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

/products/{id} update
curl -X POST {domain}/api/products/{id} -H "Accept: application/json" -H "Authorization: Bearer auth_token" -H "Content-Type: application/json" -F "name=Producto 02" -F "sku=2342323WE23E" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

/products/{id} destroy
curl -X DELETE {domain}/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 


-----------------------------------------Without authentication-------------------------------------------------
/products index
curl -X GET {domain}/api/products -H 'Accept: application/json'

/products store
curl -X POST {domain}/api/products -H 'Accept: application/json' -F "name=Producto 01" -F "sku=2342323WEWEWE" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

/products/{id} show
curl -X GET {domain}/api/products/{id} -H 'Accept: application/json' 

/products/{id} update
curl -X POST {domain}/api/products/{id} -H "Accept: application/json" -H "Content-Type: application/json" -F "name=Producto 02" -F "sku=2342323WE23E" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

/products/{id} destroy
curl -X DELETE {domain}/api/products/{id} -H 'Accept: application/json' 


.................................................PREGUNTAS ADICCIONALES............................................
1. Si se requiere manejar inventario,cómo lo haría ?.
Para manejar el inventario en el ejemplo anterior, agregaría dos columnas a la tabla producto: stock y stockMin. La columna stock indicaría la cantidad de unidades de un producto que quedan en stock, y la columna stockMin validaría la cantidad mínima para que se reporte que el producto tiene stock bajo.
Al realizar una venta, se reduciría el stock en la cantidad de productos vendidos. Si el stock es igual a stockMin, se notificaría al usuario. Si el stock de un producto llega a cero, no se permitiría realizar ventas de ese producto.
2. Si se requiere crear un reporte de ventas en el ejemplo anterior, explique cómo lo haría.
Para crear un reporte de ventas en el ejemplo anterior, lo primero que haría sería realizarle una pregunta sencilla al cliente: ¿qué cantidad y frecuencia de datos tendrá el reporte? Dependiendo de ello, elegiría la manera correcta de generar las consultas en Laravel para generar los reportes y buscaría maneras de optimizar el tiempo de respuesta de dichas consultas.
Una vez que tenga la data, buscaría una librería de Laravel para generar los reportes.
3. Si se requiere enviar estas facturas de venta 1 vez al día a un sistema externo usando un webservice SOAP en formato txt, explique cómo lo haría.
Para enviar las facturas de venta a un sistema externo usando un webservice SOAP en formato txt, escribiría un servicio web SOAP que aceptara un archivo TXT como entrada. El servicio web leería el archivo TXT y lo enviaría al sistema externo.
Es necesario tener en cuenta que, como se envían grandes cantidades de correos electrónicos diarios, se deben manejar colas en Laravel.

