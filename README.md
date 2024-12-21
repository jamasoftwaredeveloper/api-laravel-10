# Información del proyecto.
Este proyecto consiste en una API RESTful desarrollada con Laravel 10 para gestionar usuarios, productos y ventas. Permite la creación de usuarios mediante un endpoint de registro que valida los datos recibidos y los almacena en la base de datos. Además, los administradores pueden realizar operaciones CRUD sobre productos y ventas, permitiendo crear, leer, actualizar y eliminar estos registros.

La autenticación de usuarios se gestiona utilizando auth, una solución ligera que requiere un token de autenticación en la versión 2 de la API, mientras que la versión 1 no requiere autenticación. La API está diseñada sin la necesidad de formularios, ya que el registro de usuarios se realiza a través de un endpoint que valida y almacena los datos directamente.

Entre las principales características del proyecto se incluyen:

Validaciones Request de Laravel para garantizar que los datos sean correctos antes de procesarlos.
Uso de Resource para estructurar las respuestas de manera consistente.
Tests automatizados para asegurar la calidad del código.
Docker para facilitar el entorno de desarrollo y despliegue.
Documentación de la API con Swagger para ofrecer una interfaz interactiva y detallada.
Implementación del Patrón Repositorio para separar la lógica de acceso a datos.
Seeders y Factories para generar datos de prueba fácilmente.
Rutas API definidas bajo el patrón RESTful.
Uso de Traits para compartir funcionalidades entre modelos o controladores.
Migraciones para gestionar la estructura de la base de datos de manera ordenada.
Este enfoque asegura que la aplicación sea fácil de mantener, escalable y segura, aprovechando las mejores prácticas de desarrollo en Laravel. También cuenta con ejemplos básicos de uso de job, queue
Este proyecto consiste en una API RESTful desarrollada con Laravel 10 para gestionar usuarios, productos y ventas. Permite la creación de usuarios mediante un endpoint de registro que valida los datos recibidos y los almacena en la base de datos. Además, los administradores pueden realizar operaciones CRUD sobre productos y ventas, permitiendo crear, leer, actualizar y eliminar estos registros. La autenticación de usuarios se gestiona utilizando auth , una solución ligera que requiere un token de autenticación en la versión 2 de la API, mientras que la versión 1 no requiere autenticación. La API está diseñada sin la necesidad de formularios, ya que el registro de usuarios se realiza a través de un endpoint que valida y almacena los datos directamente. Entre las principales características del proyecto se incluyen: Validaciones Request de Laravel para garantizar que los datos sean correctos antes de procesarlos. Uso de Resource para estructurar las respuestas de manera consistente. Tests automatizados para asegurar la calidad del código. Docker para facilitar el entorno de desarrollo y despliegue. Documentación de la API con Swagger para ofrecer una interfaz interactiva y detallada. Implementación del Patrón Repositorio para separar la lógica de acceso a datos. Seeders y Factories para generar datos de prueba fácilmente. Rutas API definidas bajo el patrón RESTful. Uso de Traits para compartir funcionalidades entre modelos o controladores. Migraciones para gestionar la estructura de la base de datos de manera ordenada. Este enfoque asegura que la aplicación sea fácil de mantener, escalable y segura, aprovechando las mejores prácticas de desarrollo en Laravel. También cuenta con ejemplos básicos de uso de job, queue
Aptitudes: Laravel · Test unit · Docker · Swagger · Patrón repository


# DOCUMENTACIÓN
# Tener en cuenta.
Se requiere:
1. php ^8.1.
2. composer.
3. mysql.
# Paso a paso.
Se deben crear dos bases de datos, una para dev y otra para test.
Se deben configurar los archivos .env y env.testing con conexion a mysql, luego de hecho siga los siguientes pasos:
Al descargar el proyecto recuerda darle composer install y php artisan key:generate y verificar que los dos archivos .env tenga su api key
# Creación de tablas de mi base datos y llenado.
php artisan migrate:fresh --seed
# Creación de tablas de mi base datos de testing y llenado. 
php artisan migrate:fresh --seed --env=testing
# Ejecución de pruebas 
php artisan test

Usuario existente.
Usuario: test@example.com
Contraseña: 0713

//.........................................DOCUMENTACIÓN API............................................//

# Autenticación.
POST /register: Registra un nuevo usuario.
POST /login: Inicia sesión a un usuario existente.

POST /v2/logout: Cierra la sesión del usuario actual.
GET /v2/user/profile: Obtiene el perfil del usuario actual.

# Contamos con dos versiones.
1. v1 Apis sin auntenticacion.
2. v2 Apis con auntenticacion.

# Productos.

GET api/{version}/products: Obtiene todos los productos.
GET api/{version}/products/{id}: Obtiene un producto por ID.
POST api/{version}/products: Crea un nuevo producto.
PUT api/{version}/products/{id}: Actualiza un producto.
DELETE /products/{id}: Elimina un producto.
PUT /products/{product}/inactiveOrActivate: Activa o desactiva un producto.

# Ventas.
GET api/{version}/sales: Obtiene todas las ventas.
GET api/{version}/sales/{id}: Obtiene una venta por ID.
POST api/{version}/sales: Crea una nueva venta.
PUT api/{version}/sales/{id}: Actualiza una venta.
DELETE api/{version}/sales/{id}: Elimina una venta.

//...........................................CURL.................................................//
-------------------------------------------Autentication-----------------------------------------
# Nota: 
1. auth_token: lo retorna la petición del login.
2. domain: dominio o url.

curl -X POST {domain}/api/login -F "email=test@example.com" -F "password=0713"
curl -X POST {domain}/api/logout -H 'Accept: application/json'

# /products index
curl -X GET {domain}/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

# /products store
curl -X POST {domain}/api/products -H 'Accept: application/json' -H "Authorization: Bearer auth_token" -F "name=Producto 01" -F "sku=2342323WEWEWE" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

# /products/{id} show
curl -X GET {domain}/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 

# /products/{id} update
curl -X POST {domain}/api/products/{id} -H "Accept: application/json" -H "Authorization: Bearer auth_token" -H "Content-Type: application/json" -F "name=Producto 02" -F "sku=2342323WE23E" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

# /products/{id} destroy
curl -X DELETE {domain}/api/products/{id} -H 'Accept: application/json' -H "Authorization: Bearer auth_token" 


-----------------------------------------Without authentication-------------------------------------------------
# /products index
curl -X GET {domain}/api/products -H 'Accept: application/json'

# /products store
curl -X POST {domain}/api/products -H 'Accept: application/json' -F "name=Producto 01" -F "sku=2342323WEWEWE" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

# /products/{id} show
curl -X GET {domain}/api/products/{id} -H 'Accept: application/json' 

# /products/{id} update
curl -X POST {domain}/api/products/{id} -H "Accept: application/json" -H "Content-Type: application/json" -F "name=Producto 02" -F "sku=2342323WE23E" -F "description=Producto 01 test" -F "price=3000" -F "iva=19" -F "active=1" 

# /products/{id} destroy
curl -X DELETE {domain}/api/products/{id} -H 'Accept: application/json' 


.................................................DOCKER............................................

Instalar docker.
Abrir docker.

Ejecutar
docker-compose up --build  ó docker-compose up -d --build


