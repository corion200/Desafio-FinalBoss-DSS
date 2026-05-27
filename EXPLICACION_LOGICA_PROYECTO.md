# Lógica y Flujo de Trabajo del Proyecto: Gym Admin

Este documento explica detalladamente la arquitectura, el flujo de trabajo y la distribución de responsabilidades en el sistema de gestión de gimnasio **Gym Admin**.

---

## 1. Lógica General del Proyecto
El sistema está diseñado para centralizar la operación de un gimnasio, permitiendo gestionar desde el acceso de clientes y ventas de productos hasta la asignación de rutinas personalizadas y el mantenimiento del equipo. La lógica se basa en un **Control de Acceso Basado en Roles (RBAC)** que segmenta las funcionalidades críticas.

### Flujo de Trabajo Principal
1.  **Autenticación:** Todo usuario debe iniciar sesión. El sistema redirige al Dashboard correspondiente según su rol (`admin`, `recepcionista`, `coach`).
2.  **Gestión de Clientes:** La Recepción registra a los clientes. Un cliente puede comprar una membresía para tener acceso y se le puede asignar un Coach.
3.  **Operación Diaria:**
    *   Venta de productos (suplementos, agua, etc.).
    *   Gestión de pagos de membresías.
4.  **Entrenamiento:** Los Coaches visualizan a sus clientes asignados y les crean rutinas de ejercicios.
5.  **Mantenimiento:** Los Coaches reportan fallas en máquinas; el Admin supervisa y actualiza el estado de estos reportes.
6.  **Administración:** El Administrador gestiona el personal, los precios de membresías, el inventario y visualiza el balance financiero.

---

## 2. Apartados por Rol

### 🛡️ Administrador (Admin)
Es el superusuario con acceso total.
*   **Gestión de Empleados:** Alta, baja y edición de Recepcionistas y Coaches.
*   **Gestión de Productos:** Control de inventario y reposición de stock.
*   **Configuración de Membresías:** Definición de tipos de membresía (mensual, anual, etc.) y sus precios.
*   **Balance Financiero:** Visualización de ingresos totales por ventas y membresías.
*   **Supervisión de Reportes:** Revisión de reportes de equipo dañado enviados por los coaches.

### 💼 Recepcionista
Encargado de la atención al cliente y transacciones.
*   **Manejo de Clientes:** Registro y actualización de datos de socios.
*   **Ventas:** Procesamiento de ventas de productos y suscripciones a membresías.
*   **Asignación de Coach:** Vincular a un cliente con un entrenador disponible.

### 👟 Coach (Entrenador)
Enfocado en la parte deportiva y operativa del equipo.
*   **Mis Clientes:** Lista de socios asignados para seguimiento.
*   **Rutinas:** Creación y edición de planes de entrenamiento (ejercicios, series, repeticiones).
*   **Reportes de Equipo:** Notificar a la administración sobre máquinas en mal estado.

---

## 3. Reflejo en el Código

El proyecto sigue el patrón **MVC (Modelo-Vista-Controlador)** de Laravel.

### 📁 Modelos (`app/Models/`)
Representan las entidades de la base de datos y sus relaciones:
*   `User.php`: Maneja autenticación y roles (`isAdmin()`, `isCoach()`, etc.).
*   `Cliente.php`: Almacena info del socio y su relación con el Coach y Membresías.
*   `Membresia.php` y `MembresiaCliente.php`: Definen los planes y el historial de pagos de los clientes.
*   `Producto.php`, `Venta.php` y `VentaDetalle.php`: Lógica de inventario y transacciones comerciales.
*   `Rutina.php` y `Ejercicio.php`: Estructura de los planes de entrenamiento.
*   `ReporteEquipo.php`: Registro de incidencias técnicas en el gimnasio.

### 📁 Controladores (`app/Http/Controllers/`)
Organizados por carpetas según el rol:
*   **Raíz:** `ClienteController`, `ProductoController`, `VentaController` (Funcionalidad compartida).
*   **`Admin/`**: `EmpleadoController`, `MembresiaController`, `BalanceController`, `ReporteEquipoController`.
*   **`Coach/`**: `RutinaController`, `ClienteController` (Vista específica), `ReporteEquipoController` (Creación).
*   **`Auth/`**: `LoginController` para el acceso al sistema.

### 📁 Rutas (`routes/web.php`)
Las rutas utilizan **Middlewares** para proteger el acceso:
*   `auth`: Asegura que el usuario esté logueado.
*   `role:admin,recepcionista,coach`: Restringe rutas específicas según el rol del usuario.
*   **Grupos de Rutas:** Se usan prefijos como `/admin` y `/coach` para organizar los endpoints.

### 📁 Vistas (`resources/views/`)
Estructura jerárquica que coincide con los roles:
*   `admin/`: Dashboards, gestión de empleados, balance y productos.
*   `coach/`: Gestión de rutinas y creación de reportes.
*   `recepcionista/`: Vistas de ventas y gestión de clientes.
*   `layouts/app.blade.php`: Plantilla base con el sidebar dinámico que cambia según el rol.

### 🔗 Endpoints Principales
*   `GET /login`: Formulario de acceso.
*   `GET /dashboard`: Panel principal dinámico.
*   `POST /clientes/{id}/vender-membresia`: Procesa el pago de una suscripción.
*   `GET /admin/balance`: Reporte de ingresos (Solo Admin).
*   `POST /coach/reportes`: Envío de reporte de máquina dañada (Solo Coach).
*   `GET /coach/clientes/{id}/rutinas/crear`: Constructor de rutinas.

---
*Este documento es una guía técnica para entender la implementación actual de Gym Admin.*
