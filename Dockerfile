FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip gd mbstring

# Habilitar mod_rewrite de Apache (útil para rutas amigables)
RUN a2enmod rewrite

# Copiar el código fuente al contenedor
COPY . /var/www/html/

# Ajustar permisos (el usuario de Apache es www-data)
RUN chown -R www-data:www-data /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ejecutar composer install si existe composer.json
# (Esto es opcional en dev, pero crítico en prod si no subimos vendor)
RUN if [ -f composer.json ]; then \
        composer install --no-dev --optimize-autoloader; \
    fi

# Exponer el puerto 80 (Railway lo detecta automáticamente via PORT env var, pero Apache escucha en 80 por defecto)
EXPOSE 80
