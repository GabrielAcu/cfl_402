# Sistema de Gestión CFL 402

## 📄 Descripción del Proyecto
Este sistema web integral ha sido desarrollado para optimizar la administración y gestión académica del **Centro de Formación Laboral 402**. La plataforma centraliza las operaciones diarias, permitiendo un control eficiente de alumnos, instructores, cursos y matriculas.

El objetivo principal es modernizar los procesos administrativos, eliminando el uso de planillas físicas y reduciendo redundancias, garantizando al mismo tiempo la seguridad e integridad de los datos institucionales.

## ✨ Características Principales

### 👨‍🎓 Gestión Académica
- **Alumnos**: Alta, baja y modificación de legajos completos. Historial de inscripciones.
- **Instructores**: Gestión de personal docente, asignación de cursos y horarios.
- **Cursos**: Administración de oferta académica, cupos y turnos (Mañana, Tarde, Noche).
- **Inscripciones**: Sistema de matriculación ágil con validación de cupos en tiempo real.

### 🛡️ Seguridad y Control
- **Roles y Permisos**: Acceso jerarquizado para SuperAdmin, Administradores e Instructores.
- **Auditoría**: Registro detallado de accesos y acciones críticas.
- **Protección de Datos**: Contraseñas encriptadas (Bcrypt), protección contra inyección SQL (PDO) y ataques XSS/CSRF.

### 💻 Experiencia de Usuario (UI/UX)
- **Diseño Moderno**: Interfaz limpia intuitiva con Modo Oscuro nativo.
- **Responsive**: Adaptable a dispositivos de escritorio y tabletas.
- **Feedback Visual**: Sistema de notificaciones y modales para una interacción fluida.

## �️ Stack Tecnológico

El proyecto está construido sobre una arquitectura robusta y estándar:

- **Backend**: PHP 8.2 (Vanilla, orientado a objetos).
- **Base de Datos**: MySQL / MariaDB (Estructura relacional optimizada).
- **Frontend**: HTML5, CSS3 (Variables, Flexbox/Grid), JavaScript ES6+.
- **Servidor Web**: Apache (XAMPP/LAMP).

## 🚀 Instalación y Despliegue

1.  **Requisitos**: Servidor Web con PHP 8.0+ y MySQL.
2.  **Base de Datos**: Importar el script `database_structure.sql` (incluido en la raíz).
3.  **Configuración**:
    *   Renombrar `config/env.example.php` a `config/env.php`.
    *   Configurar las credenciales de conexión a la BD.
4.  **Acceso**:
    *   Navegar a la URL del proyecto.
    *   Credenciales iniciales provistas en la documentación interna.

---
© 2025 CFL 402 - Todos los derechos reservados.
