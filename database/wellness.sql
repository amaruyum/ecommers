-- Base de datos: wellness
-- Este archivo define la estructura (esquema) de la base de datos wellness.

-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS wellness CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE wellness;

-- =========================
-- TABLAS BÁSICAS
-- =========================

CREATE TABLE USUARIO (
    id_usuario         BIGINT AUTO_INCREMENT PRIMARY KEY,
    correo_electronico VARCHAR(255) NOT NULL UNIQUE,
    contrasena         VARCHAR(255) NOT NULL,
    nombre_completo    VARCHAR(255) NOT NULL,
    telefono           VARCHAR(50),
    fecha_registro     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP NULL,
    estado_cuenta      VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ROL (
    id_rol     BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE USUARIO_ROL (
    id_usuario BIGINT NOT NULL,
    id_rol     BIGINT NOT NULL,
    PRIMARY KEY (id_usuario, id_rol),
    CONSTRAINT fk_usuario_rol_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_usuario_rol_rol
        FOREIGN KEY (id_rol)
        REFERENCES ROL(id_rol)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- SUBTIPOS DE USUARIO
-- =========================

CREATE TABLE USUARIO_CLIENTE (
    id_usuario   BIGINT PRIMARY KEY,
    ciudad       VARCHAR(255),
    direccion    VARCHAR(255),
    preferencias VARCHAR(255),
    CONSTRAINT fk_usuario_cliente_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE USUARIO_INSTRUCTOR (
    id_instructor      BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario         BIGINT NOT NULL UNIQUE,
    descripcion_perfil VARCHAR(255),
    especialidad       VARCHAR(255),
    CONSTRAINT fk_usuario_instructor_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE USUARIO_ADMINISTRADOR (
    id_usuario BIGINT PRIMARY KEY,
    CONSTRAINT fk_usuario_admin_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- CATEGORÍAS E ITEMS
-- =========================

CREATE TABLE CATEGORIA (
    id_categoria BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ITEM (
    id_item      BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_categoria BIGINT NOT NULL,
    nombre       VARCHAR(255) NOT NULL,
    descripcion  VARCHAR(255),
    estado       VARCHAR(50),
    precio       DECIMAL(8,2),
    CONSTRAINT fk_item_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES CATEGORIA(id_categoria)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Subtipo PRODUCTO (ITEM_PRODUCTO)
CREATE TABLE ITEM_PRODUCTO (
    id_item           BIGINT PRIMARY KEY,
    marca             VARCHAR(255),
    fecha_elaboracion DATE,
    fecha_caducidad   DATE,
    stock_disponible  INT,
    CONSTRAINT fk_item_producto_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- SEDE E ITEM SERVICIO
-- =========================

CREATE TABLE SEDE (
    id_sede   BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(255) NOT NULL,
    ciudad    VARCHAR(255),
    direccion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ITEM_SERVICIO (
    id_item               BIGINT PRIMARY KEY,
    id_instructor         BIGINT NOT NULL,
    id_sede               BIGINT NOT NULL,
    tipo_servicio         VARCHAR(100),
    fecha_inicio          TIMESTAMP NULL,
    fecha_fin             TIMESTAMP NULL,
    itinerario            VARCHAR(255),
    lugar                 VARCHAR(255),
    cupos_totales         INT,
    cupos_disponibles     INT,
    politicas_cancelacion VARCHAR(255),
    CONSTRAINT fk_item_servicio_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_item_servicio_instructor
        FOREIGN KEY (id_instructor)
        REFERENCES USUARIO_INSTRUCTOR(id_instructor)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_item_servicio_sede
        FOREIGN KEY (id_sede)
        REFERENCES SEDE(id_sede)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- CARRITO Y DETALLE DE CARRITO
-- =========================

CREATE TABLE CARRITO (
    id_carrito BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_cliente BIGINT NOT NULL,
    fecha      TIMESTAMP NULL,
    estado     VARCHAR(50),
    CONSTRAINT fk_carrito_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES USUARIO_CLIENTE(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE CARRITO_ITEM (
    id_carrito_item BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_carrito      BIGINT NOT NULL,
    id_item         BIGINT NOT NULL,
    cantidad        INT NOT NULL,
    precio          DECIMAL(8,2) NOT NULL,
    subtotal        DECIMAL(8,2) NOT NULL,
    CONSTRAINT fk_carrito_item_carrito
        FOREIGN KEY (id_carrito)
        REFERENCES CARRITO(id_carrito)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_carrito_item_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- PEDIDO Y DETALLE
-- =========================

CREATE TABLE PEDIDO (
    id_pedido     BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_cliente    BIGINT NOT NULL,
    fecha         TIMESTAMP NULL,
    estado        VARCHAR(50),
    total_general DECIMAL(8,2),
    CONSTRAINT fk_pedido_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES USUARIO_CLIENTE(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PEDIDO_DETALLE (
    id_pedido_detalle BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_pedido         BIGINT NOT NULL,
    id_item           BIGINT NOT NULL,
    cantidad          INT NOT NULL,
    precio            DECIMAL(8,2) NOT NULL,
    subtotal          DECIMAL(8,2) NOT NULL,
    CONSTRAINT fk_pedido_detalle_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES PEDIDO(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pedido_detalle_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- CUPONES
-- =========================

CREATE TABLE CUPON (
    id_cupon        BIGINT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(100) NOT NULL UNIQUE,
    descripcion     VARCHAR(255),
    valor_descuento DECIMAL(8,2),
    fecha_expiracion DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PUEDE_TENER_CUPON (
    id_puede_tener BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_cupon       BIGINT NOT NULL,
    id_pedido      BIGINT NOT NULL,
    CONSTRAINT fk_puede_tener_cupon
        FOREIGN KEY (id_cupon)
        REFERENCES CUPON(id_cupon)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_puede_tener_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES PEDIDO(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- MÉTODOS DE PAGO Y PAGOS
-- =========================

CREATE TABLE METODO_PAGO (
    id_metodo_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre_metodo  VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PAGO (
    id_pago        BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_pedido      BIGINT NOT NULL,
    id_metodo_pago BIGINT NOT NULL,
    total          DECIMAL(8,2),
    fecha_pago     TIMESTAMP NULL,
    estado         TEXT,
    CONSTRAINT fk_pago_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES PEDIDO(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pago_metodo
        FOREIGN KEY (id_metodo_pago)
        REFERENCES METODO_PAGO(id_metodo_pago)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- NOTIFICACIONES
-- =========================

CREATE TABLE NOTIFICACION (
    id_notificacion   BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario        BIGINT NOT NULL,
    tipo              VARCHAR(100),
    contenido_mensaje VARCHAR(255),
    fecha_envio       TIMESTAMP NULL,
    estado            VARCHAR(50),
    CONSTRAINT fk_notificacion_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- INVENTARIO
-- =========================

CREATE TABLE INVENTARIO (
    id_inventario    BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_item          BIGINT NOT NULL,
    cantidad         INT NOT NULL,
    tipo_movimiento  VARCHAR(100),
    fecha_movimiento TIMESTAMP NULL,
    descripcion      VARCHAR(255),
    CONSTRAINT fk_inventario_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- RESERVAS
-- =========================

CREATE TABLE RESERVA (
    id_reserva    BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_cliente    BIGINT NOT NULL,
    id_servicio   BIGINT NOT NULL,
    fecha_reserva TIMESTAMP NULL,
    estado        VARCHAR(50),
    CONSTRAINT fk_reserva_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES USUARIO_CLIENTE(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reserva_servicio
        FOREIGN KEY (id_servicio)
        REFERENCES ITEM_SERVICIO(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- CONTENIDO
-- =========================

CREATE TABLE CONTENIDO (
    id_contenido     BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_administrador BIGINT NOT NULL,
    titulo           VARCHAR(255) NOT NULL,
    cuerpo           VARCHAR(255),
    fecha_creacion   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo_contenido   VARCHAR(100),
    CONSTRAINT fk_contenido_admin
        FOREIGN KEY (id_administrador)
        REFERENCES USUARIO_ADMINISTRADOR(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- RESEÑAS
-- =========================

CREATE TABLE RESENA (
    id_resena  BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_cliente BIGINT NOT NULL,
    id_item    BIGINT NOT NULL,
    puntuacion INT,
    comentario VARCHAR(255),
    fecha      TIMESTAMP NULL,
    CONSTRAINT fk_resena_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES USUARIO_CLIENTE(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_resena_item
        FOREIGN KEY (id_item)
        REFERENCES ITEM(id_item)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- LOG DE TRANSACCIONES
-- =========================

CREATE TABLE LOG_TRANSACCION (
    id_log     BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_pedido  BIGINT NULL,
    id_pago    BIGINT NULL,
    id_usuario BIGINT NOT NULL,
    fecha      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_log_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES PEDIDO(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_log_pago
        FOREIGN KEY (id_pago)
        REFERENCES PAGO(id_pago)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_log_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES USUARIO(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
