#!/bin/bash
set -e

echo "=== Railway Apache Startup Script ==="

# SOLUCIÓN AGRESIVA AL PROBLEMA DE MPM
# Remover TODOS los MPMs habilitados y habilitar solo mpm_prefork
echo "Limpiando configuración de MPM..."

# Paso 1: Remover todos los MPMs habilitados
rm -f /etc/apache2/mods-enabled/mpm_*.load
rm -f /etc/apache2/mods-enabled/mpm_*.conf

# Paso 2: Habilitar solo mpm_prefork
if [ -f /etc/apache2/mods-available/mpm_prefork.load ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
fi

if [ -f /etc/apache2/mods-available/mpm_prefork.conf ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi

echo "MPM configurado: solo mpm_prefork habilitado"

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
