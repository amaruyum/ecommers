<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('USUARIO', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('correo_electronico', 255)->unique();
            $table->string('contrasena', 255);
            $table->string('nombre_completo', 255);
            $table->string('telefono', 50)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_modificacion')->nullable();
            $table->string('estado_cuenta', 50);
        });

        Schema::create('ROL', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre_rol', 100);
        });

        Schema::create('USUARIO_ROL', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->primary(['id_usuario', 'id_rol']);
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_rol')->references('id_rol')->on('ROL')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('USUARIO_CLIENTE', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->primary();
            $table->string('ciudad', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('preferencias', 255)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('USUARIO_INSTRUCTOR', function (Blueprint $table) {
            $table->id('id_instructor');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('descripcion_perfil', 255)->nullable();
            $table->string('especialidad', 255)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('USUARIO_ADMINISTRADOR', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario')->primary();
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('CATEGORIA', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 255);
        });

        Schema::create('ITEM', function (Blueprint $table) {
            $table->id('id_item');
            $table->unsignedBigInteger('id_categoria');
            $table->string('nombre', 255);
            $table->string('descripcion', 255)->nullable();
            $table->string('estado', 50)->nullable();
            $table->decimal('precio', 8, 2)->nullable();
            $table->foreign('id_categoria')->references('id_categoria')->on('CATEGORIA')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('ITEM_PRODUCTO', function (Blueprint $table) {
            $table->unsignedBigInteger('id_item')->primary();
            $table->string('marca', 255)->nullable();
            $table->date('fecha_elaboracion')->nullable();
            $table->date('fecha_caducidad')->nullable();
            $table->integer('stock_disponible')->nullable();
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('SEDE', function (Blueprint $table) {
            $table->id('id_sede');
            $table->string('nombre', 255);
            $table->string('ciudad', 255)->nullable();
            $table->string('direccion', 255)->nullable();
        });

        Schema::create('ITEM_SERVICIO', function (Blueprint $table) {
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
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_instructor')->references('id_instructor')->on('USUARIO_INSTRUCTOR')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_sede')->references('id_sede')->on('SEDE')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('CARRITO', function (Blueprint $table) {
            $table->id('id_carrito');
            $table->unsignedBigInteger('id_cliente');
            $table->timestamp('fecha')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('USUARIO_CLIENTE')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('CARRITO_ITEM', function (Blueprint $table) {
            $table->id('id_carrito_item');
            $table->unsignedBigInteger('id_carrito');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->foreign('id_carrito')->references('id_carrito')->on('CARRITO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('PEDIDO', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_cliente');
            $table->timestamp('fecha')->nullable();
            $table->string('estado', 50)->nullable();
            $table->decimal('total_general', 8, 2)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('USUARIO_CLIENTE')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('PEDIDO_DETALLE', function (Blueprint $table) {
            $table->id('id_pedido_detalle');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->foreign('id_pedido')->references('id_pedido')->on('PEDIDO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('CUPON', function (Blueprint $table) {
            $table->id('id_cupon');
            $table->string('codigo', 100)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->decimal('valor_descuento', 8, 2)->nullable();
            $table->date('fecha_expiracion')->nullable();
        });

        Schema::create('PUEDE_TENER_CUPON', function (Blueprint $table) {
            $table->id('id_puede_tener');
            $table->unsignedBigInteger('id_cupon');
            $table->unsignedBigInteger('id_pedido');
            $table->foreign('id_cupon')->references('id_cupon')->on('CUPON')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_pedido')->references('id_pedido')->on('PEDIDO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('METODO_PAGO', function (Blueprint $table) {
            $table->id('id_metodo_pago');
            $table->string('nombre_metodo', 100);
        });

        Schema::create('PAGO', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_metodo_pago');
            $table->decimal('total', 8, 2)->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->text('estado')->nullable();
            $table->foreign('id_pedido')->references('id_pedido')->on('PEDIDO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('METODO_PAGO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('NOTIFICACION', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('tipo', 100)->nullable();
            $table->string('contenido_mensaje', 255)->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('INVENTARIO', function (Blueprint $table) {
            $table->id('id_inventario');
            $table->unsignedBigInteger('id_item');
            $table->integer('cantidad');
            $table->string('tipo_movimiento', 100)->nullable();
            $table->timestamp('fecha_movimiento')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('RESERVA', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_servicio');
            $table->timestamp('fecha_reserva')->nullable();
            $table->string('estado', 50)->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('USUARIO_CLIENTE')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_servicio')->references('id_item')->on('ITEM_SERVICIO')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('CONTENIDO', function (Blueprint $table) {
            $table->id('id_contenido');
            $table->unsignedBigInteger('id_administrador');
            $table->string('titulo', 255);
            $table->string('cuerpo', 255)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('tipo_contenido', 100)->nullable();
            $table->foreign('id_administrador')->references('id_usuario')->on('USUARIO_ADMINISTRADOR')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('RESENA', function (Blueprint $table) {
            $table->id('id_resena');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_item');
            $table->integer('puntuacion')->nullable();
            $table->string('comentario', 255)->nullable();
            $table->timestamp('fecha')->nullable();
            $table->foreign('id_cliente')->references('id_usuario')->on('USUARIO_CLIENTE')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_item')->references('id_item')->on('ITEM')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('LOG_TRANSACCION', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_pedido')->nullable();
            $table->unsignedBigInteger('id_pago')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->foreign('id_pedido')->references('id_pedido')->on('PEDIDO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_pago')->references('id_pago')->on('PAGO')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('USUARIO')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $tables = [
            'LOG_TRANSACCION', 'RESENA', 'CONTENIDO', 'RESERVA', 'INVENTARIO', 'NOTIFICACION',
            'PAGO', 'METODO_PAGO', 'PUEDE_TENER_CUPON', 'CUPON', 'PEDIDO_DETALLE', 'PEDIDO',
            'CARRITO_ITEM', 'CARRITO', 'ITEM_SERVICIO', 'SEDE', 'ITEM_PRODUCTO', 'ITEM',
            'CATEGORIA', 'USUARIO_ADMINISTRADOR', 'USUARIO_INSTRUCTOR', 'USUARIO_CLIENTE',
            'USUARIO_ROL', 'ROL', 'USUARIO'
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
