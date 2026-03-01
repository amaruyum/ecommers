-- ============================================================
--  Wellness DB — Schema completo
--  Ejecutado automáticamente al levantar el contenedor
-- ============================================================

-- ========================= USUARIOS =========================

CREATE TABLE usuario (
    id_usuario          BIGSERIAL PRIMARY KEY,
    correo_electronico  VARCHAR(255) NOT NULL UNIQUE,
    contrasena          VARCHAR(255) NOT NULL,
    nombre_completo     VARCHAR(255) NOT NULL,
    telefono            VARCHAR(50),
    fecha_registro      TIMESTAMP DEFAULT NOW(),
    fecha_modificacion  TIMESTAMP,
    estado_cuenta       VARCHAR(50) DEFAULT 'activo'
);

CREATE TABLE rol (
    id_rol      BIGSERIAL PRIMARY KEY,
    nombre_rol  VARCHAR(100) NOT NULL
);

CREATE TABLE usuario_rol (
    id_usuario  BIGINT NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_rol      BIGINT NOT NULL REFERENCES rol(id_rol)         ON DELETE CASCADE,
    PRIMARY KEY (id_usuario, id_rol)
);

CREATE TABLE usuario_cliente (
    id_usuario   BIGINT PRIMARY KEY REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    ciudad       VARCHAR(255),
    direccion    VARCHAR(255),
    preferencias VARCHAR(255)
);

CREATE TABLE usuario_instructor (
    id_instructor      BIGSERIAL PRIMARY KEY,
    id_usuario         BIGINT UNIQUE NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    descripcion_perfil TEXT,
    especialidad       VARCHAR(255)
);

CREATE TABLE usuario_administrador (
    id_usuario      BIGINT PRIMARY KEY REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_administrador BIGSERIAL UNIQUE
);

-- ========================= CATÁLOGO =========================

CREATE TABLE categoria (
    id_categoria  BIGSERIAL PRIMARY KEY,
    nombre        VARCHAR(255) NOT NULL,
    descripcion   TEXT,
    tipo          VARCHAR(50),
    estado        VARCHAR(50) DEFAULT 'activo'
);

CREATE TABLE item (
    id_item      BIGSERIAL PRIMARY KEY,
    id_categoria BIGINT NOT NULL REFERENCES categoria(id_categoria) ON DELETE CASCADE,
    nombre       VARCHAR(255) NOT NULL,
    descripcion  TEXT,
    estado       VARCHAR(50),
    precio       DECIMAL(8,2),
    tipo         VARCHAR(50)
);

CREATE TABLE item_producto (
    id_item           BIGINT PRIMARY KEY REFERENCES item(id_item) ON DELETE CASCADE,
    marca             VARCHAR(255),
    fecha_elaboracion DATE,
    fecha_caducidad   DATE,
    stock_disponible  INTEGER
);

CREATE TABLE sede (
    id_sede    BIGSERIAL PRIMARY KEY,
    nombre     VARCHAR(255) NOT NULL,
    ciudad     VARCHAR(255),
    direccion  VARCHAR(255)
);

CREATE TABLE item_servicio (
    id_item               BIGINT PRIMARY KEY REFERENCES item(id_item) ON DELETE CASCADE,
    id_instructor         BIGINT REFERENCES usuario_instructor(id_instructor) ON DELETE SET NULL,
    id_sede               BIGINT REFERENCES sede(id_sede) ON DELETE SET NULL,
    tipo_servicio         VARCHAR(100),
    fecha_inicio          TIMESTAMP,
    fecha_fin             TIMESTAMP,
    itinerario            TEXT,
    lugar                 VARCHAR(255),
    cupos_totales         INTEGER,
    cupos_disponibles     INTEGER,
    politicas_cancelacion TEXT,
    nivel_dificultad      VARCHAR(50)
);

-- ========================= CONTENIDO / BLOG =================

CREATE TABLE contenido (
    id_contenido     BIGSERIAL PRIMARY KEY,
    id_administrador BIGINT REFERENCES usuario_administrador(id_usuario) ON DELETE SET NULL,
    titulo           TEXT NOT NULL,
    cuerpo           TEXT NOT NULL,
    fecha_creacion   TIMESTAMP DEFAULT NOW(),
    tipo_contenido   VARCHAR(50)
);

-- ========================= CARRITO ==========================

CREATE TABLE carrito (
    id_carrito  BIGSERIAL PRIMARY KEY,
    id_cliente  BIGINT NOT NULL REFERENCES usuario_cliente(id_usuario) ON DELETE CASCADE,
    fecha       TIMESTAMP DEFAULT NOW(),
    estado      VARCHAR(50)
);

CREATE TABLE carrito_item (
    id_carrito_item  BIGSERIAL PRIMARY KEY,
    id_carrito       BIGINT NOT NULL REFERENCES carrito(id_carrito)  ON DELETE CASCADE,
    id_item          BIGINT NOT NULL REFERENCES item(id_item)         ON DELETE CASCADE,
    cantidad         INTEGER NOT NULL,
    precio           DECIMAL(8,2) NOT NULL,
    subtotal         DECIMAL(8,2) NOT NULL
);

-- ========================= PEDIDOS ==========================

CREATE TABLE pedido (
    id_pedido      BIGSERIAL PRIMARY KEY,
    id_cliente     BIGINT NOT NULL REFERENCES usuario_cliente(id_usuario) ON DELETE CASCADE,
    fecha          TIMESTAMP DEFAULT NOW(),
    estado         VARCHAR(50),
    total_general  DECIMAL(8,2),
    nombre         VARCHAR(255),
    email          VARCHAR(255),
    telefono       VARCHAR(50),
    ciudad         VARCHAR(255),
    direccion      VARCHAR(255),
    notas          TEXT,
    metodo_pago    VARCHAR(50)
);

CREATE TABLE pedido_detalle (
    id_pedido_detalle  BIGSERIAL PRIMARY KEY,
    id_pedido          BIGINT NOT NULL REFERENCES pedido(id_pedido) ON DELETE CASCADE,
    id_item            BIGINT NOT NULL REFERENCES item(id_item)     ON DELETE CASCADE,
    cantidad           INTEGER NOT NULL,
    precio             DECIMAL(8,2) NOT NULL,
    subtotal           DECIMAL(8,2) NOT NULL
);

-- ========================= CUPONES ==========================

CREATE TABLE cupon (
    id_cupon          BIGSERIAL PRIMARY KEY,
    codigo            VARCHAR(100) NOT NULL UNIQUE,
    descripcion       VARCHAR(255),
    valor_descuento   DECIMAL(8,2),
    fecha_expiracion  DATE
);

CREATE TABLE puede_tener_cupon (
    id_puede_tener  BIGSERIAL PRIMARY KEY,
    id_cupon        BIGINT NOT NULL REFERENCES cupon(id_cupon)    ON DELETE CASCADE,
    id_pedido       BIGINT NOT NULL REFERENCES pedido(id_pedido)  ON DELETE CASCADE
);

-- ========================= PAGOS ============================

CREATE TABLE metodo_pago (
    id_metodo_pago  BIGSERIAL PRIMARY KEY,
    nombre_metodo   VARCHAR(100) NOT NULL
);

CREATE TABLE pago (
    id_pago          BIGSERIAL PRIMARY KEY,
    id_pedido        BIGINT NOT NULL REFERENCES pedido(id_pedido)             ON DELETE CASCADE,
    id_metodo_pago   BIGINT NOT NULL REFERENCES metodo_pago(id_metodo_pago)   ON DELETE CASCADE,
    total            DECIMAL(8,2),
    fecha_pago       TIMESTAMP DEFAULT NOW(),
    estado           TEXT
);

-- ========================= OPERACIONES ======================

CREATE TABLE notificacion (
    id_notificacion    BIGSERIAL PRIMARY KEY,
    id_usuario         BIGINT NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    tipo               VARCHAR(100),
    contenido_mensaje  VARCHAR(255),
    fecha_envio        TIMESTAMP DEFAULT NOW(),
    estado             VARCHAR(50)
);

CREATE TABLE inventario (
    id_inventario     BIGSERIAL PRIMARY KEY,
    id_item           BIGINT NOT NULL REFERENCES item(id_item) ON DELETE CASCADE,
    cantidad          INTEGER NOT NULL,
    tipo_movimiento   VARCHAR(100),
    fecha_movimiento  TIMESTAMP DEFAULT NOW(),
    descripcion       VARCHAR(255)
);

CREATE TABLE reserva (
    id_reserva   BIGSERIAL PRIMARY KEY,
    id_cliente   BIGINT NOT NULL REFERENCES usuario_cliente(id_usuario) ON DELETE CASCADE,
    id_servicio  BIGINT NOT NULL REFERENCES item_servicio(id_item)      ON DELETE CASCADE,
    fecha        TIMESTAMP DEFAULT NOW(),
    estado       VARCHAR(50)
);

CREATE TABLE resena (
    id_resena    BIGSERIAL PRIMARY KEY,
    id_usuario   BIGINT NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_item      BIGINT NOT NULL REFERENCES item(id_item)       ON DELETE CASCADE,
    calificacion INTEGER CHECK (calificacion BETWEEN 1 AND 5),
    comentario   TEXT,
    fecha        TIMESTAMP DEFAULT NOW()
);

CREATE TABLE log_transaccion (
    id_log       BIGSERIAL PRIMARY KEY,
    id_usuario   BIGINT REFERENCES usuario(id_usuario) ON DELETE SET NULL,
    accion       VARCHAR(255),
    tabla        VARCHAR(100),
    fecha        TIMESTAMP DEFAULT NOW(),
    detalle      TEXT
);

-- ========================= SANCTUM ==========================

CREATE TABLE personal_access_tokens (
    id             BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id   BIGINT       NOT NULL,
    name           VARCHAR(255) NOT NULL,
    token          VARCHAR(64)  NOT NULL UNIQUE,
    abilities      TEXT,
    last_used_at   TIMESTAMP,
    expires_at     TIMESTAMP,
    created_at     TIMESTAMP,
    updated_at     TIMESTAMP
);

CREATE INDEX idx_personal_access_tokens_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);