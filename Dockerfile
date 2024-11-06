# Usar una imagen base de PHP
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    unzip \
    curl \
    gnupg2 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Copiar archivos de la aplicación
COPY . .

# Instalar dependencias de PHP sin ejecutar scripts de Composer
RUN composer install --no-scripts --no-autoloader && \
    composer dump-autoload

# Copiar el script de entrada y hacerlo ejecutable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Exponer el puerto
EXPOSE 8000

# Configurar el script de entrada
ENTRYPOINT ["/usr/local/bin/start.sh"]
