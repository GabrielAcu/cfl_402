<?php
// Ejemplo de configuración para InfinityFree / Producción
// Renombra este archivo a 'env.php' y súbelo a la carpeta /config en tu hosting

// Configuración de la Base de Datos (Datos proporcionados por el panel de InfinityFree)
putenv("DB_HOST=sqlXXX.infinityfree.com"); // "MySQL Hostname"
putenv("DB_NAME=if0_382...");             // "MySQL Database Name"
putenv("DB_USER=if0_382...");             // "MySQL Username"
putenv("DB_PASS=tu_contraseña_vpanel");   // "MySQL Password" (la del panel, no la de acceso a la cuenta)
putenv("DB_PORT=3306");

// URL del sitio (Opcional, el sistema intenta detectarlo solo, pero mejor definirlo)
// putenv("APP_URL=http://tu-sitio.infinityfreeapp.com");
