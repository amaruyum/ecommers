<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\CarritoItemController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ContenidoController;
use App\Http\Controllers\Api\CuponController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ItemProductoController;
use App\Http\Controllers\Api\ItemServicioController;
use App\Http\Controllers\Api\LogTransaccionController;
use App\Http\Controllers\Api\MetodoPagoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\PedidoDetalleController;
use App\Http\Controllers\Api\PuedeTenerCuponController;
use App\Http\Controllers\Api\ResenaController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\SedeController;
use App\Http\Controllers\Api\UsuarioAdministradorController;
use App\Http\Controllers\Api\UsuarioClienteController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\UsuarioInstructorController;
use App\Http\Controllers\Api\UsuarioRolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Wellness
|--------------------------------------------------------------------------
*/

// ─── AUTH (públicas) ──────────────────────────────────────────────────────
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// ─── AUTH (protegidas con Sanctum) ────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});

// ─── USUARIOS Y ROLES ─────────────────────────────────────────────────────
Route::apiResource('usuarios',  UsuarioController::class);
Route::apiResource('roles',     RolController::class);

Route::get('usuario-roles',        [UsuarioRolController::class, 'index']);
Route::post('usuario-roles',       [UsuarioRolController::class, 'store']);
Route::get('usuario-roles/show',   [UsuarioRolController::class, 'show']);
Route::put('usuario-roles',        [UsuarioRolController::class, 'update']);
Route::delete('usuario-roles',     [UsuarioRolController::class, 'destroy']);

Route::apiResource('usuario-clientes',        UsuarioClienteController::class);
Route::apiResource('usuario-instructores',    UsuarioInstructorController::class);
Route::apiResource('usuario-administradores', UsuarioAdministradorController::class)->except(['update']);

// ─── CATÁLOGO ──────────────────────────────────────────────────────────────
Route::apiResource('categorias',    CategoriaController::class);
Route::apiResource('items',         ItemController::class);
Route::apiResource('item-productos', ItemProductoController::class);
Route::apiResource('sedes',         SedeController::class);
Route::apiResource('item-servicios', ItemServicioController::class);

// ─── CARRITO ───────────────────────────────────────────────────────────────
Route::apiResource('carritos',      CarritoController::class);
Route::apiResource('carrito-items', CarritoItemController::class);

// ─── PEDIDOS ───────────────────────────────────────────────────────────────
Route::apiResource('pedidos',         PedidoController::class);
Route::apiResource('pedido-detalles', PedidoDetalleController::class);

// ─── CUPONES Y PAGOS ───────────────────────────────────────────────────────
Route::apiResource('cupones',              CuponController::class);
Route::apiResource('puede-tener-cupones',  PuedeTenerCuponController::class);
Route::apiResource('metodo-pagos',         MetodoPagoController::class);
Route::apiResource('pagos',                PagoController::class);

// ─── OPERACIONES ───────────────────────────────────────────────────────────
Route::apiResource('notificaciones',   NotificacionController::class);
Route::apiResource('inventarios',      InventarioController::class);
Route::apiResource('reservas',         ReservaController::class);
Route::apiResource('contenidos',       ContenidoController::class);
Route::apiResource('resenas',          ResenaController::class);
Route::apiResource('log-transacciones', LogTransaccionController::class);