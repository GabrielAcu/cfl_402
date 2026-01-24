#!/bin/bash

# Script para subir base de datos a Railway usando Git Bash
# Ajustar ruta de XAMPP si es necesaria, pero usualmente Git Bash encuentra los ejecutables si están en path
# Si no, usamos ruta absoluta estilo Unix

MYSQL_PATH="/c/xampp/mysql/bin/mysql.exe"

if [ ! -f "$MYSQL_PATH" ]; then
    echo "No encontré mysql.exe en $MYSQL_PATH"
    echo "Por favor, verifica tu instalación de XAMPP."
    exit 1
fi

echo "========================================="
echo "   SUBIDA DE BASE DE DATOS A RAILWAY     "
echo "========================================="
echo "Necesitarás los datos de conexión de Railway."
echo ""

read -p "Host (ej: roundhouse.proxy.rlwy.net): " DB_HOST
read -p "Puerto (ej: 12345): " DB_PORT
read -p "Usuario (ej: root): " DB_USER
read -s -p "Contraseña: " DB_PASS
echo ""
read -p "Nombre Base de Datos (ej: railway): " DB_NAME

echo ""
echo "Iniciando importación..."

# Comando para importar
# Usamos -p$DB_PASS sin espacio.
"$MYSQL_PATH" -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database_structure.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ ¡EXITO! La base de datos se ha cargado correctamente."
else
    echo ""
    echo "❌ Hubo un error. Revisa los datos y la conexión."
fi

read -p "Presiona Enter para salir..."
