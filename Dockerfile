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

# FIX: Deshabilitar MPMs conflictivos y habilitar solo mpm_prefork
# Apache solo puede tener un MPM activo a la vez
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

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

# Copiar script de inicio personalizado
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto (Railway usa la variable $PORT dinámicamente)
EXPOSE 80

# Usar el script personalizado como punto de entrada
CMD ["/usr/local/bin/docker-entrypoint.sh"]
