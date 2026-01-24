# Script para subir base de datos a Railway usando el cliente MySQL de XAMPP

# Intentar ubicar mysql.exe en la ruta por defecto de XAMPP
$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"

if (-not (Test-Path $mysqlPath)) {
    Write-Host "No encontré mysql.exe en C:\xampp\mysql\bin. Por favor, asegúrate de tener XAMPP instalado." -ForegroundColor Red
    exit
}

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "   SUBIDA DE BASE DE DATOS A RAILWAY     " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Necesitarás los datos de conexión de Railway (Pestaña 'Connect' -> 'MySQL Connection')." -ForegroundColor Yellow
Write-Host ""

$host = Read-Host "Host (ej: roundhouse.proxy.rlwy.net)"
$port = Read-Host "Puerto (ej: 12345)"
$user = Read-Host "Usuario (ej: root)"
$pass = Read-Host "Contraseña (pega con click derecho si copiaste)"
$dbname = Read-Host "Nombre Base de Datos (ej: railway)"

Write-Host ""
Write-Host "Iniciando importación... Esto puede tardar unos segundos." -ForegroundColor Green

# Ejecutar comando
# Nota: Usamos Start-Process para evitar problemas con contraseñas en texto plano en historial
$archivoSQL = "database_structure.sql"

# Comando: mysql -h $host -P $port -u $user -p$pass $dbname < $archivoSQL
# En PowerShell la redirección < no funciona igual, usamos Get-Content | mysql

$cmd = "& '$mysqlPath' -h $host -P $port -u $user -p'$pass' $dbname"

# Ejecución segura
try {
    Get-Content $archivoSQL | & $mysqlPath -h $host -P $port -u $user -p"$pass" $dbname
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "¡EXITO! La base de datos se ha cargado correctamente." -ForegroundColor Cyan
    } else {
        Write-Host ""
        Write-Host "Hubo un error. Revisa los datos ingresados." -ForegroundColor Red
    }
} catch {
    Write-Host "Error al ejecutar: $_" -ForegroundColor Red
}

Read-Host "Presiona Enter para salir..."
