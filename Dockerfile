# Imagen base con PHP 8.2 y Composer
FROM php:8.2-cli

# Instalar extensiones necesarias y utilidades
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear directorio de la app
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto HTTP que usará php artisan serve
EXPOSE 8000

# Comando por defecto: levantar Laravel en puerto 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
