# Flujo de Ejecución — Seeders Wellness

## Estructura de archivos

```
wellness-laravel/
└── database/
    └── seeders/
        ├── DatabaseSeeder.php     ← Director de orquesta
        ├── UsuarioSeeder.php
        ├── CategoriaSeeder.php
        ├── ItemSeeder.php
        └── ContenidoSeeder.php
```

---

## Flujo de ejecución

```
php artisan db:seed
        │
        ▼
DatabaseSeeder.php
        │
        ├── 1️⃣  UsuarioSeeder      → tabla: usuario, usuario_cliente,
        │                             usuario_instructor, usuario_administrador
        │
        ├── 2️⃣  CategoriaSeeder    → tabla: categoria
        │
        ├── 3️⃣  ItemSeeder         → tabla: item, item_servicio, item_producto
        │                             (necesita IDs de categorias e instructores)
        │
        └── 4️⃣  ContenidoSeeder    → tabla: contenido
                                      (necesita ID del administrador)
```

> ⚠️ **El orden importa.** Si se invierte, falla por claves foráneas inexistentes.

---

## Lo que hace cada Seeder

### 1. UsuarioSeeder
| Acción | Detalle |
|--------|---------|
| Limpia | `usuario_administrador` → `usuario_instructor` → `usuario_cliente` → `usuario` |
| Inserta | 1 admin, 4 instructores, 8 clientes |
| Passwords | Todos con `Hash::make('password123')` |

### 2. CategoriaSeeder
| Acción | Detalle |
|--------|---------|
| Limpia | `categoria` |
| Inserta | 8 categorías (Yoga, Retiros, Nutrición, Fitness...) |

### 3. ItemSeeder
| Acción | Detalle |
|--------|---------|
| Limpia | `item_servicio` → `item_producto` → `item` |
| Consulta | IDs reales de `categoria` e `usuario_instructor` |
| Inserta | 8 servicios + 8 productos con sus tablas relacionadas |
| Especial | Si `stock = 0`, cambia `estado` a `agotado` automáticamente |

### 4. ContenidoSeeder
| Acción | Detalle |
|--------|---------|
| Limpia | `contenido` |
| Consulta | ID del administrador ya creado |
| Inserta | 7 publicaciones (artículos, noticia, video, anuncio) |

---

## Datos de prueba generados

### Usuarios
| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@wellness.com | password123 |
| Instructora | maria.paz@wellness.com | password123 |
| Instructor | carlos.rios@wellness.com | password123 |
| Cliente | ana.torres@email.com | password123 |

### Items
| Tipo | Cantidad | Estado |
|------|----------|--------|
| Retiros | 3 | activo |
| Capacitaciones | 2 | activo |
| Talleres / Clases / Eventos | 3 | activo |
| Productos | 7 activos + 1 agotado | — |

### Contenido Blog
| Tipo | Cantidad |
|------|----------|
| Artículos | 4 |
| Noticia | 1 |
| Anuncio | 1 |
| Video | 1 |

---

## Comandos

```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar uno específico
php artisan db:seed --class=ItemSeeder

# Reset completo: borra tablas + migraciones + seeders
php artisan migrate:fresh --seed
```

> 💡 `migrate:fresh --seed` es el comando más útil en desarrollo.
> Deja la BD limpia y con datos frescos en un solo paso.

---

## Patrón interno de cada Seeder

```
1. LIMPIAR   → DELETE en orden inverso (hijos antes que padres)
2. CONSULTAR → Obtener IDs reales de tablas dependientes
3. INSERTAR  → insertGetId() cuando el ID se necesita en otra tabla
               insert()        cuando no se necesita el ID
```
