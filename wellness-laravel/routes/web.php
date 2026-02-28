<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-info', function () {
    $base = url('api');
    return response()->json([
        'message' => 'Wellness API - Laravel',
        'base_url' => $base,
        'endpoints' => [
            'usuarios' => $base . '/usuarios',
            'roles' => $base . '/roles',
            'categorias' => $base . '/categorias',
            'sedes' => $base . '/sedes',
            'items' => $base . '/items',
            'pedidos' => $base . '/pedidos',
            'cupones' => $base . '/cupones',
            'y más...' => 'Ver routes/api.php',
        ],
    ], 200, [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
});
