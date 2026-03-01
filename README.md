# Wellness - Laravel

Proyecto Wellness migrado a **Laravel** (PHP). Misma base de datos `wellness` y mismas tablas que el proyecto PHP original.

## Requisitos

- PHP 8.1+
- Composer
- MySQL (XAMPP) con base de datos `wellness`

## Configuración

1. **Base de datos**: En `.env` ya está configurado:
   - `DB_DATABASE=wellness`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=` (ajusta si usas contraseña)

2. **Migraciones** (solo si creas la BD desde cero):
   ```bash
   php artisan migrate
   ```
   Si ya tienes la base `wellness` creada con el script `database/wellness.sql` del proyecto original, **no ejecutes** migrate o comenta la migración de wellness en `database/migrations/`.

3. **Servidor de desarrollo**:
   ```bash
   php artisan serve
   ```
   Abre: http://localhost:8000

## API (JSON)

Todas las entidades tienen CRUD vía API bajo el prefijo `/api`:

| Recurso | GET (listar) | POST (crear) | GET (ver) | PUT (actualizar) | DELETE |
|---------|--------------|--------------|-----------|------------------|--------|
| Usuarios | `/api/usuarios` | `/api/usuarios` | `/api/usuarios/{id}` | `/api/usuarios/{id}` | `/api/usuarios/{id}` |
| Roles | `/api/roles` | ... | ... | ... | ... |
| Categorías | `/api/categorias` | ... | ... | ... | ... |
| Sedes | `/api/sedes` | ... | ... | ... | ... |
| Items | `/api/items` | ... | ... | ... | ... |
| Pedidos | `/api/pedidos` | ... | ... | ... | ... |
| Cupones | `/api/cupones` | ... | ... | ... | ... |
| ... | (resto en `routes/api.php`) |

- **Listar**: `GET /api/usuarios` → JSON con todos los registros.
- **Crear**: `POST /api/usuarios` con body JSON (ej: `{"nombre_completo":"Juan","correo_electronico":"j@mail.com","contrasena":"123"}`).
- **Ver uno**: `GET /api/usuarios/1`.
- **Actualizar**: `PUT /api/usuarios/1` con body JSON.
- **Eliminar**: `DELETE /api/usuarios/1`.

Ruta de ayuda: **GET /api-info** devuelve un resumen de la API en JSON.

## Estructura

- **Modelos**: `app/Models/` (Usuario, Rol, Categoria, Item, Pedido, etc.) con tablas en MAYÚSCULAS como en el esquema original.
- **Controladores API**: `app/Http/Controllers/Api/`.
- **Migración**: `database/migrations/2024_01_01_000001_create_wellness_tables.php` (todas las tablas wellness).

## coneccion docker my sql
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password


FORWARD_DB_PORT=3306
