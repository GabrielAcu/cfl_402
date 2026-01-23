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

# SOLUCIÓN DEFINITIVA AL PROBLEMA DE MPM:
# La imagen php:8.2-apache viene con mpm_event por defecto
# Necesitamos cambiarlo a mpm_prefork ANTES de que Apache intente iniciarse
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Habilitar mod_rewrite de Apache (útil para rutas amigables)
RUN a2enmod rewrite

# Copiar script de inicio personalizado PRIMERO (antes del código)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copiar el código fuente al contenedor
COPY . /var/www/html/

# Ajustar permisos (el usuario de Apache es www-data)
RUN chown -R www-data:www-data /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ejecutar composer install si existe composer.json
RUN if [ -f composer.json ]; then \
    composer install --no-dev --optimize-autoloader; \
    fi

# Exponer el puerto (Railway usa la variable $PORT dinámicamente)
EXPOSE 80

# Usar el script personalizado como punto de entrada
CMD ["/usr/local/bin/docker-entrypoint.sh"]
