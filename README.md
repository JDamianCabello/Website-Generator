# CMS Laravel Inertia & Vue 3

Este proyecto es un **Content Management System (CMS)** desarrollado con **Laravel**, **Inertia.js** y **Vue 3**. Está diseñado para ofrecer funcionalidades de autenticación, gestión de usuarios, roles y permisos (usando [Spatie Permission](https://github.com/spatie/laravel-permission)), gestión de páginas y plantillas, y personalización de la web a través de componentes modernos y flexibles.

## Tabla de Contenidos

- [Características Actuales](#características-actuales)
- [Funcionalidades en Desarrollo](#funcionalidades-en-desarrollo)
- [Instalación y Configuración](#instalación-y-configuración)
- [Uso](#uso)
- [Registro de Versiones](#registro-de-versiones)
- [Política de Inclusión de Mejoras](#política-de-inclusión-de-mejoras)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)

## Características Actuales

- **Dashboard**: Acceso al panel principal con información general y enlaces a las distintas secciones del CMS.
- **Gestión de Usuarios**:
  - Visualización, creación, edición y eliminación de usuarios.
  - Asignación de roles y permisos a los usuarios.
- **Gestión de Roles**:
  - Visualización, creación y edición de roles.
  - Configuración de permisos para cada rol.
- **Gestión de Páginas** (WIP):
  - Creación y edición de contenido para páginas.
  - Visualización y organización de páginas.
- **Gestión de Plantillas**:
  - Creación y edición de plantillas para las páginas.
  - Generación de contenido mediante inteligencia artificial.
  - Previsualización de borradores.
- **Configuración Web (Toast Settings)**:
  - Edición y restauración de ajustes de la web.

Las rutas definidas en el proyecto incluyen endpoints para cada una de estas funcionalidades, utilizando middleware para controlar el acceso basado en autenticación y permisos.

## Funcionalidades en Desarrollo

- **Completar la Gestión de Páginas**: Actualmente en estado de desarrollo (WIP). Se planea añadir funcionalidades para gestionar de forma completa la creación, edición, eliminación y organización de páginas.
- **Mejoras en el Editor de Plantillas**: Incorporar nuevas herramientas para la edición en tiempo real y mejoras en la integración con la generación de contenido AI.
- **Optimización de Permisos y Roles**: Refinar la configuración de permisos para una gestión más granular y segura.
- **Documentación y Soporte Multilenguaje**: Ampliar la documentación y añadir soporte para múltiples idiomas.

## Instalación y Configuración

1. **Clonar el repositorio:**
   ```
   git clone https://github.com/JDamianCabello/Website-Generator.git
   cd Website-Generator
   ```

2. **Instalar dependencias:**
   ```
   composer install
   npm install
   ```

3. **Configurar el entorno:**
   - Copiar el archivo de ejemplo de entorno:
     ```
     cp .env.example .env
     ```
   - Configurar la conexión a la base de datos y otros parámetros en el archivo .env.
   - Generar la clave de aplicación:
     ```
     php artisan key:generate
     ```

4. **Migrar y sembrar la base de datos:**
   ```
   php artisan migrate --seed
   ```

5. **Compilar los assets:**
   ```
   npm run dev
   ```

## Uso

Credenciales del usuario administrador por defecto:
- **Email**: `admin@admin.com`
- **Contraseña**: `admin`

1. Accede a la aplicación mediante la URL configurada (por ejemplo, http://localhost:8000).
2. Utiliza el sistema de autenticación para ingresar al Dashboard.
3. Desde el Dashboard, podrás gestionar usuarios, roles, páginas, plantillas y la configuración de la web, de acuerdo a los permisos asignados.

## Registro de Versiones

### Versión 0.1
**Estado**: Versión inicial del proyecto.

**Características Incluidas**:
- Implementación del Dashboard.
- Gestión de usuarios con asignación de roles y permisos.
- Gestión de roles y permisos (integración con Spatie Permission).
- Gestión básica de plantillas con generación de contenido AI y previsualización.
- Configuración de la web (Toast Settings).
- Funcionalidad básica para la gestión de páginas (en desarrollo / WIP).

## Política de Inclusión de Mejoras

Este proyecto está abierto a mejoras que contribuyan a su crecimiento y eficiencia. Se seguirán las siguientes pautas para la inclusión de mejoras:

1. **Respeto y Colaboración**: Todas las propuestas deben fomentar un ambiente de respeto y colaboración.
2. **Claridad en la Propuesta**: Las mejoras propuestas deben estar claramente descritas, justificadas y documentadas.
3. **Compatibilidad**: Se evaluará que las mejoras sean compatibles con la arquitectura actual del proyecto y que no rompan funcionalidades existentes.
4. **Revisión y Pruebas**: Toda propuesta pasará por un proceso de revisión y pruebas para garantizar su estabilidad y calidad.
5. **Feedback**: Se proporcionará feedback constructivo a todos los colaboradores para mejorar la calidad y funcionalidad del proyecto.

## Contribuciones

¡Contribuciones, sugerencias y reportes de errores son bienvenidos!
Si deseas colaborar, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama con la funcionalidad o corrección:
   ```
   git checkout -b feature/nueva-funcionalidad
   ```
3. Realiza tus cambios y haz commit:
   ```
   git commit -m "Agrega nueva funcionalidad..."
   ```
4. Envía un pull request explicando tus cambios.

## Licencia

Este proyecto se distribuye bajo la Licencia MIT.