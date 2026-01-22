#!/bin/bash
set -e

echo "=== Railway Apache Startup Script ==="

# Railway asigna un puerto dinámico en la variable $PORT
# Si no existe, usar 80 por defecto (para desarrollo local)
PORT=${PORT:-80}

echo "Configurando Apache para escuchar en puerto: $PORT"

# Actualizar ports.conf
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf

# Actualizar VirtualHost en 000-default.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

echo "Configuración de Apache completada"
echo "Iniciando Apache en primer plano..."

# Iniciar Apache en primer plano
exec apache2-foreground
