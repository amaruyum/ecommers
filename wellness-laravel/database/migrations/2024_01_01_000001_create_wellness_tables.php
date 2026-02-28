<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ========================= USUARIOS =========================
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->string('correo_electronico', 255)->unique();
            $table->string('contrasena', 255);
            $table->string('nombre_completo', 255);
            $table->string('telefono', 50)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_modificacion')->nullable();
            $table->string('estado_cuenta', 50)->default('activo');
        });

        Schema::create('rol', function (Blueprint $table) {
            $table->bigIncrements('id_rol');
            $table->string('nombre_rol', 100);
        });

        Schema::create('usuario_rol', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->primary(['id_usuario', 'id_rol']);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
            $table->foreign('id_rol')->references('id_rol')->on('rol')->cascadeOnDelete();
        });

        // ========================= SUBTIPOS USUARIO =========================
        Schema::create('usuario_cliente', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->primary();
            $table->string('ciudad', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('preferencias', 255)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
        });

        Schema::create('usuario_instructor', function (Blueprint $table) {
            $table->bigIncrements('id_instructor');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('descripcion_perfil', 255)->nullable();
            $table->string('especialidad', 255)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
        });

        Schema::create('usuario_administrador', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->primary();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
        });

        // ========================= CATALOGO =========================
        Schema::create('categoria', function (Blueprint $table) {
            $table->bigIncrements('id_categoria');
            $table->string('nombre', 255);
        });

        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id_item');
            $table->unsignedBigInteger('id_categoria');
            $table->string('nombre', 255);
            $table->string('descripcion', 255)->nullable();
            $table->string('estado', 50)->nullable();
            $table->decimal('precio', 8, 2)->nullable();
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria')->cascadeOnDelete();
        });

        Schema::create('item_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('id_item')->primary();
            $table->string('marca', 255)->nullable();
            $table->date('fecha_elaboracion')->nullable();
            $table->date('fecha_caducidad')->nullable();
            $table->integer('stock_disponible')->nullable();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
        });

        Schema::create('sede', function (Blueprint $table) {
            $table->bigIncrements('id_sede');
            $table->string('nombre', 255);
            $table->string('ciudad', 255)->nullable();
            $table->string('direccion', 255)->nullable();
        });

        Schema::create('item_servicio', function (Blueprint $table) {
            $table->unsignedBigInteger('id_item')->primary();
            $table->unsignedBigInteger('id_instructor');
            $table->unsignedBigInteger('id_sede');
            $table->string('tipo_servicio', 100)->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->string('itinerario', 255)->nullable();
            $table->string('lugar', 255)->nullable();
            $table->integer('cupos_totales')->nullable();
            $table->integer('cupos_disponibles')->nullable();
            $table->string('politicas_cancelacion', 255)->nullable();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
            $table->foreign('id_instructor')->references('id_instructor')->on('usuario_instructor')->cascadeOnDelete();
            $table->foreign('id_sede')->references('id_sede')->on('sede')->cascadeOnDelete();
        });

        // ========================= CARRITO =========================
        Schema::create('carrito', function (Blueprint $table) {
            $table->bigIncrements('id_carrito');
            $table->unsignedBigInteger('id_cliente');
            $table->timestamp('fecha')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('usuario_cliente')->cascadeOnDelete();
        });

        Schema::create('carrito_item', function (Blueprint $table) {
            $table->bigIncrements('id_carrito_item');
            $table->unsignedBigInteger('id_carrito');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->foreign('id_carrito')->references('id_carrito')->on('carrito')->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
        });

        // ========================= PEDIDOS =========================
        Schema::create('pedido', function (Blueprint $table) {
            $table->bigIncrements('id_pedido');
            $table->unsignedBigInteger('id_cliente');
            $table->timestamp('fecha')->nullable();
            $table->string('estado', 50)->nullable();
            $table->decimal('total_general', 8, 2)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('usuario_cliente')->cascadeOnDelete();
        });

        Schema::create('pedido_detalle', function (Blueprint $table) {
            $table->bigIncrements('id_pedido_detalle');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
        });

        // ========================= CUPONES =========================
        Schema::create('cupon', function (Blueprint $table) {
            $table->bigIncrements('id_cupon');
            $table->string('codigo', 100)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->decimal('valor_descuento', 8, 2)->nullable();
            $table->date('fecha_expiracion')->nullable();
        });

        Schema::create('puede_tener_cupon', function (Blueprint $table) {
            $table->bigIncrements('id_puede_tener');
            $table->unsignedBigInteger('id_cupon');
            $table->unsignedBigInteger('id_pedido');
            $table->foreign('id_cupon')->references('id_cupon')->on('cupon')->cascadeOnDelete();
            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->cascadeOnDelete();
        });

        // ========================= PAGOS =========================
        Schema::create('metodo_pago', function (Blueprint $table) {
            $table->bigIncrements('id_metodo_pago');
            $table->string('nombre_metodo', 100);
        });

        Schema::create('pago', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_metodo_pago');
            $table->decimal('total', 8, 2)->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->text('estado')->nullable();
            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->cascadeOnDelete();
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('metodo_pago')->cascadeOnDelete();
        });

        // ========================= OPERACIONES =========================
        Schema::create('notificacion', function (Blueprint $table) {
            $table->bigIncrements('id_notificacion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('tipo', 100)->nullable();
            $table->string('contenido_mensaje', 255)->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->bigIncrements('id_inventario');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->string('tipo_movimiento', 100)->nullable();
            $table->timestamp('fecha_movimiento')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
        });

        Schema::create('reserva', function (Blueprint $table) {
            $table->bigIncrements('id_reserva');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_servicio');
            $table->timestamp('fecha_reserva')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('usuario_cliente')->cascadeOnDelete();
            $table->foreign('id_servicio')->references('id_item')->on('item_servicio')->cascadeOnDelete();
        });

        Schema::create('contenido', function (Blueprint $table) {
            $table->bigIncrements('id_contenido');
            $table->unsignedBigInteger('id_administrador');
            $table->string('titulo', 255);
            $table->string('cuerpo', 255)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('tipo_contenido', 100)->nullable();
            $table->foreign('id_administrador')->references('id_usuario')->on('usuario_administrador')->cascadeOnDelete();
        });

        Schema::create('resena', function (Blueprint $table) {
            $table->bigIncrements('id_resena');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_item');
            $table->integer('puntuacion')->nullable();
            $table->string('comentario', 255)->nullable();
            $table->timestamp('fecha')->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('usuario_cliente')->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('item')->cascadeOnDelete();
        });

        Schema::create('log_transaccion', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('id_pedido')->nullable();
            $table->unsignedBigInteger('id_pago')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->cascadeOnDelete();
            $table->foreign('id_pago')->references('id_pago')->on('pago')->cascadeOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $tables = [
            'log_transaccion', 'resena', 'contenido', 'reserva', 'inventario', 'notificacion',
            'pago', 'metodo_pago', 'puede_tener_cupon', 'cupon', 'pedido_detalle', 'pedido',
            'carrito_item', 'carrito', 'item_servicio', 'sede', 'item_producto', 'item',
            'categoria', 'usuario_administrador', 'usuario_instructor', 'usuario_cliente',
            'usuario_rol', 'rol', 'usuario'
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};