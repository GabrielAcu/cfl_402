#!/bin/bash
# Script de inicio para Railway que configura Apache dinámicamente

# Railway asigna un puerto dinámico en la variable $PORT
# Apache por defecto escucha en 80, necesitamos cambiarlo

if [ -n "$PORT" ]; then
    echo "Configurando Apache para escuchar en puerto $PORT"
    
    # Modificar la configuración de Apache para usar el puerto de Railway
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
fi

# Iniciar Apache en primer plano
apache2-foreground
