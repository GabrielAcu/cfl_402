<?php
// ⚠️ ESTE ES UN ARCHIVO DE EJEMPLO (PLANTILLA) ⚠️
// NO GUARDES TUS CONTRASEÑAS REALES AQUÍ.

/*
INSTRUCCIONES PARA EL EQUIPO:
1. Crea un archivo nuevo dentro de la carpeta 'config/' llamado: env.php
   (El archivo env.php ya está ignorado por Git, así que es seguro).

2. Copia todo el código de abajo y pégalo en tu nuevo archivo env.php.

3. En env.php, cambia los valores por tus credenciales reales de XAMPP/MySQL.
*/

// Ejemplo de configuración (Edita esto en tu env.php):

putenv("DB_HOST=nombre-del-servidor");// Generalmente es 'localhost'
putenv("DB_NAME=nombre-de-la-base-de-datos");// El nombre de nuestra base de datos
putenv("DB_USER=nombre-de-usuario");// Por defecto en XAMPP es 'root'
putenv("DB_PASS=contraseña");// Por defecto en XAMPP suele estar vacío
