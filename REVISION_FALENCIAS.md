# 🔍 REVISIÓN COMPLETA DEL PROYECTO - FALENCIAS ENCONTRADAS

**Fecha de revisión:** 2025-01-27  
**Proyecto:** CFL 402 - Sistema de Gestión de Autoescuela

---

## 🚨 PROBLEMAS CRÍTICOS DE SEGURIDAD

### 1. **CONTRASEÑAS EN TEXTO PLANO** ⚠️ CRÍTICO
**Ubicación:** `auth/login.php` (líneas 26-30), Base de datos `usuarios`

**Problema:**
- Las contraseñas se almacenan y comparan en texto plano
- No se usa `password_hash()` ni `password_verify()`
- Vulnerable a ataques de fuerza bruta y exposición de credenciales

**Código afectado:**
```php
// auth/login.php línea 26-30
$sql_login = "SELECT * FROM usuarios WHERE nombre = :nombre AND contrasenia = :contrasenia";
$stm->execute([
    ':nombre' => $userName,
    ':contrasenia' => $pass  // ❌ Sin hash
]);
```

**Solución requerida:**
- Implementar `password_hash()` al crear/actualizar usuarios
- Usar `password_verify()` en el login
- Migrar contraseñas existentes a hash

---

### 2. **ARCHIVO ENV.PHP CON CREDENCIALES EXPUESTO** ⚠️ CRÍTICO
**Ubicación:** `config/env.php`

**Problema:**
- El archivo `env.php` contiene credenciales reales de base de datos
- Aunque está en `.gitignore`, si se sube al repositorio por error, expone credenciales
- El archivo `env.example.php` también tiene valores de ejemplo que podrían confundir

**Solución requerida:**
- Verificar que `env.php` esté en `.gitignore` (ya está)
- Asegurar que nunca se suba al repositorio
- Considerar usar variables de entorno del sistema

---

### 3. **VULNERABILIDAD XSS (Cross-Site Scripting)** ⚠️ CRÍTICO
**Ubicación:** Múltiples archivos

**Archivos afectados:**
- `admin/crud/alumnos/modificar.php` (líneas 41, 53, 57, 64, 70, 79, 85, 92, 98, 105, 112, 118)
- `admin/crud/cursos/tabla_cursos.php` (líneas 44-49)
- `admin/crud/cursos/nuevo_curso.php` (líneas 69, 81)
- `admin/crud/instructores/eliminar_instructor.php` (línea 46)

**Problema:**
- Datos de usuario se insertan directamente en HTML sin escapar
- Permite ejecución de código JavaScript malicioso

**Ejemplo:**
```php
// admin/crud/alumnos/modificar.php línea 41
echo "<h2>Modificar Alumno: $alumno[nombre] $alumno[apellido] </h2>";
// ❌ Sin htmlspecialchars()
```

**Solución requerida:**
- Usar `htmlspecialchars($variable, ENT_QUOTES, 'UTF-8')` en todos los outputs
- O usar sintaxis `<?= htmlspecialchars($var) ?>` en templates

---

### 4. **FALTA DE VALIDACIÓN DE ENTRADA** ⚠️ ALTO
**Ubicación:** Múltiples archivos CRUD

**Problema:**
- No se valida formato de email
- No se valida formato de teléfono
- No se valida longitud de campos
- No se sanitizan datos antes de insertar

**Archivos afectados:**
- `admin/crud/alumnos/crear.php` - Valida nombre/apellido pero no email
- `admin/crud/instructores/agregar_instructor.php` - Sin validaciones
- `admin/crud/cursos/crear_curso.php` - Validación mínima

**Solución requerida:**
- Validar email con `filter_var($email, FILTER_VALIDATE_EMAIL)`
- Validar teléfono con regex
- Validar DNI (formato argentino)
- Sanitizar todos los inputs

---

### 5. **FALTA DE PROTECCIÓN CSRF** ⚠️ ALTO
**Ubicación:** Todos los formularios

**Problema:**
- No hay tokens CSRF en los formularios
- Vulnerable a ataques Cross-Site Request Forgery

**Solución requerida:**
- Implementar tokens CSRF
- Validar tokens en cada POST

---

### 6. **ERROR EN LOGOUT.PHP** ⚠️ MEDIO
**Ubicación:** `auth/logout.php` (línea 4)

**Problema:**
- Accede a `$_SESSION['user']` sin verificar si existe
- Puede generar warnings/errores

**Código:**
```php
if($_SESSION['user']){  // ❌ Puede no existir
    unset($_SESSION['user']);
}
```

**Solución:**
```php
if(isset($_SESSION['user'])){
    unset($_SESSION['user']);
}
```

---

## 🐛 ERRORES DE CÓDIGO

### 7. **ERRORES DE SINTAXIS EN MODIFICAR.PHP** ⚠️ ALTO
**Ubicación:** `admin/crud/alumnos/modificar.php`

**Problemas encontrados:**
- Línea 48: Falta comilla de cierre en `value=$id_alumno'>`
- Línea 110: Falta `=` en `<div class'campo'>` (debe ser `class='campo'`)
- Línea 125: Textarea sin valor inicial (debería mostrar `$alumno['observaciones']`)
- Línea 139: Punto y coma después de `</form>;` (no debería estar)

**Código problemático:**
```php
// Línea 48
<input class='input-modify' type='hidden' name='id_alumno' value=$id_alumno'>
// ❌ Falta comilla antes de $id_alumno

// Línea 110
<div class'campo'>
// ❌ Falta = después de class

// Línea 125
<textarea class='input-modify' name='observaciones' id='observaciones-alumno' placeholder='Observacione'> </textarea>
// ❌ Falta value="<?= htmlspecialchars($alumno['observaciones']) ?>"
```

---

### 8. **CÓDIGO DUPLICADO Y ARCHIVOS INNECESARIOS** ⚠️ MEDIO
**Ubicación:** Varios

**Problemas:**
- `admin/crud/cursos/paginado (1).php` - Archivo duplicado (tiene espacio y paréntesis en nombre)
- `admin/crud/instructores/procesar_modifcacion_instructor.php` - Error de tipeo en nombre (debería ser "modificacion")
- `admin/crud/alumnos/crear.php` tiene código HTML al inicio que no se usa (líneas 1-12)

---

### 9. **FALTA DE VALIDACIÓN DE PERMISOS** ⚠️ MEDIO
**Ubicación:** Varios archivos CRUD

**Problema:**
- Algunos archivos tienen `requireLogin()` pero no verifican roles específicos
- Comentarios indican que había validación de roles pero está deshabilitada

**Ejemplos:**
```php
// admin/crud/instructores/eliminar_instructor.php líneas 15-18
// if (!isSuperAdmin()) {
//     header('Location: /cfl_402/index.php');
//     exit();
// }
// ❌ Validación comentada
```

---

### 10. **FALTA DE AUTENTICACIÓN EN ALGUNOS ARCHIVOS** ⚠️ ALTO
**Ubicación:** `admin/crud/instructores/agregar_instructor.php`

**Problema:**
- No tiene `requireLogin()` ni validación de autenticación
- Cualquiera puede agregar instructores si conoce la URL

**Código:**
```php
// admin/crud/instructores/agregar_instructor.php
// ❌ No tiene requireLogin()
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
// Falta: require_once BASE_PATH . '/auth/check.php';
// Falta: requireLogin();
```

---

### 11. **MANEJO DE ERRORES EXPONE INFORMACIÓN** ⚠️ MEDIO
**Ubicación:** Múltiples archivos

**Problema:**
- Mensajes de error muestran detalles técnicos que pueden ayudar a atacantes
- `$e->getMessage()` expone información de la base de datos

**Ejemplos:**
```php
// admin/crud/alumnos/crear.php línea 115
echo "Ocurrió un error al insertar los datos: " . $e->getMessage();
// ❌ Expone detalles técnicos
```

**Solución:**
- Mostrar mensajes genéricos al usuario
- Registrar errores detallados en logs
- No exponer stack traces en producción

---

### 12. **INCONSISTENCIA EN PREPARED STATEMENTS** ⚠️ MEDIO
**Ubicación:** `admin/crud/alumnos/procesar_modificacion.php`

**Problema:**
- Mezcla marcadores de posición `?` con `:nombre`
- Inconsistente con el resto del código que usa `:nombre`

**Código:**
```php
// Línea 32 - Usa ?
$consulta = $conn->prepare("UPDATE alumnos SET nombre = ?, apellido = ?, ...");

// Debería usar :nombre para consistencia
$consulta = $conn->prepare("UPDATE alumnos SET nombre = :nombre, apellido = :apellido, ...");
```

---

## 📋 PROBLEMAS DE ESTRUCTURA Y MEJORES PRÁCTICAS

### 13. **FALTA DE VALIDACIÓN DE EMAIL** ⚠️ MEDIO
**Ubicación:** Todos los formularios que reciben email

**Problema:**
- No se valida formato de email antes de insertar
- Puede almacenar emails inválidos

**Solución:**
```php
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    fallido("Email inválido");
    exit();
}
```

---

### 14. **FALTA DE VALIDACIÓN DE DNI** ⚠️ MEDIO
**Ubicación:** Formularios de alumnos e instructores

**Problema:**
- No se valida formato de DNI argentino
- No se verifica unicidad antes de insertar (aunque la BD tiene constraint)

---

### 15. **REDIRECCIONES CON RUTAS HARDCODEADAS** ⚠️ BAJO
**Ubicación:** Múltiples archivos

**Problema:**
- Rutas hardcodeadas como `/cfl_402/` dificultan portabilidad
- Ya existe `BASE_URL` en `config/path.php` pero no se usa consistentemente

**Ejemplos:**
```php
header('Location: /cfl_402/admin');
// Debería ser:
header('Location: ' . BASE_URL . '/admin');
```

---

### 16. **FALTA DE HEADERS DE SEGURIDAD** ⚠️ MEDIO
**Ubicación:** Todos los archivos PHP

**Problema:**
- No se establecen headers de seguridad HTTP
- Falta protección XSS en headers
- Falta protección contra clickjacking

**Solución:**
- Agregar headers en `include/header.php`:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: DENY`
  - `X-XSS-Protection: 1; mode=block`

---

### 17. **DEBUG CODE EN PRODUCCIÓN** ⚠️ BAJO
**Ubicación:** `auth/login.php` línea 10

**Problema:**
```php
echo "LOGIN: Entró a login.php<br>";
// ❌ Debug code que no debería estar en producción
```

---

### 18. **COMENTARIOS Y CÓDIGO COMENTADO** ⚠️ BAJO
**Ubicación:** Varios archivos

**Problema:**
- Código comentado que debería eliminarse o implementarse
- Comentarios en español e inglés mezclados

---

## 🔧 PROBLEMAS DE BASE DE DATOS

### 19. **CONTRASEÑAS EN TEXTO PLANO EN BD** ⚠️ CRÍTICO
**Ubicación:** Tabla `usuarios` en `database_structure.sql`

**Problema:**
- Las contraseñas de ejemplo están en texto plano
- Campo `contrasenia` es `varchar(50)` - muy corto para hashes

**Solución:**
- Cambiar tipo de columna a `varchar(255)` para almacenar hashes
- Migrar contraseñas existentes a hash

---

### 20. **FALTA DE ÍNDICES EN ALGUNAS BÚSQUEDAS** ⚠️ BAJO
**Ubicación:** Consultas de búsqueda

**Problema:**
- Búsquedas por nombre/apellido pueden ser lentas sin índices apropiados
- Ya hay índices en DNI, pero no en nombre/apellido para búsquedas LIKE

---

## 📊 RESUMEN POR PRIORIDAD

### 🔴 CRÍTICO (Resolver inmediatamente)
1. Contraseñas en texto plano
2. Vulnerabilidades XSS
3. Archivo env.php con credenciales
4. Contraseñas en texto plano en BD

### 🟠 ALTO (Resolver pronto)
5. Falta de validación de entrada
6. Falta de protección CSRF
7. Errores de sintaxis en modificar.php
8. Falta de autenticación en algunos archivos
9. Inconsistencia en prepared statements

### 🟡 MEDIO (Mejorar)
10. Error en logout.php
11. Falta de validación de permisos
12. Manejo de errores expone información
13. Falta de validación de email/DNI
14. Headers de seguridad faltantes

### 🟢 BAJO (Mejoras generales)
15. Rutas hardcodeadas
16. Debug code en producción
17. Código comentado
18. Índices de BD

---

## ✅ RECOMENDACIONES GENERALES

1. **Implementar sistema de logging** para errores y actividades de usuarios
2. **Crear tests unitarios** para funciones críticas
3. **Documentar API/endpoints** si se expande el proyecto
4. **Implementar rate limiting** en login para prevenir fuerza bruta
5. **Usar HTTPS** en producción
6. **Implementar backup automático** de base de datos
7. **Revisar y actualizar dependencias** en composer.json regularmente
8. **Implementar validación del lado del cliente** (JavaScript) además del servidor

---

**Total de problemas encontrados:** 20  
**Críticos:** 4  
**Altos:** 5  
**Medios:** 7  
**Bajos:** 4

