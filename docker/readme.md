# 🐳 Wellness DB — Docker PostgreSQL

Base de datos PostgreSQL del proyecto Wellness lista para levantar con Docker.

---

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y corriendo

---

## Levantar la base de datos

```bash
# 1. Entrar a esta carpeta
cd docker

# 2. Levantar el contenedor
docker compose up -d

# 3. Verificar que está corriendo
docker compose ps
```

Listo. La base de datos ya está disponible en `localhost:5432`.

---

## Credenciales

| Campo    | Valor          |
|----------|----------------|
| Host     | localhost      |
| Puerto   | 5432           |
| Base de datos | wellness_db |
| Usuario  | wellness_user  |
| Contraseña | wellness_pass |

---

## Configurar el archivo `.env` de Laravel

Abre `wellness-laravel/.env` y reemplaza la sección de base de datos:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=wellness_db
DB_USERNAME=wellness_user
DB_PASSWORD=wellness_pass
```

---

## Correr migraciones y seeders

Con el contenedor corriendo, en la carpeta `wellness-laravel/`:

```bash
# Solo migraciones (si usas las de Laravel)
php artisan migrate

# O si prefieres usar el init.sql directamente (ya lo hizo Docker)
# Solo corre los seeders:
php artisan db:seed
```

> ⚠️ Si usas `init.sql` directamente NO corras `php artisan migrate`,
> ya que las tablas fueron creadas por el script SQL automáticamente.

---

## Comandos útiles

```bash
# Ver logs del contenedor
docker compose logs -f

# Detener el contenedor (conserva los datos)
docker compose stop

# Eliminar el contenedor y los datos
docker compose down -v

# Conectarse a psql dentro del contenedor
docker exec -it wellness_db psql -U wellness_user -d wellness_db
```

---

## Conectar desde pgAdmin

1. Abre pgAdmin
2. Click derecho en **Servers → Register → Server**
3. En la pestaña **General**: nombre `Wellness Docker`
4. En la pestaña **Connection**:
   - Host: `localhost`
   - Port: `5432`
   - Database: `wellness_db`
   - Username: `wellness_user`
   - Password: `wellness_pass`
5. Click **Save**

---

## Estructura de archivos

```
docker/
├── docker-compose.yml   ← configuración del contenedor
├── init.sql             ← schema completo de la BD
└── README.md            ← este archivo
```