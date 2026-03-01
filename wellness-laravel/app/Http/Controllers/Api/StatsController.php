<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function dashboard(): JsonResponse
    {
        // ── Conteos generales ─────────────────────────────────
        $usuarios      = DB::table('usuario')->count();
        $clientes      = DB::table('usuario_cliente')->count();
        $instructores  = DB::table('usuario_instructor')->count();
        $admins        = DB::table('usuario_administrador')->count();

        $totalItems    = DB::table('item')->count();
        $itemsActivos  = DB::table('item')->where('estado', 'activo')->count();
        $itemsAgotados = DB::table('item')->where('estado', 'agotado')->count();
        $itemsInactivos= DB::table('item')->where('estado', 'inactivo')->count();

        $totalCategorias = DB::table('categoria')->count();
        $catActivas      = DB::table('categoria')->where('estado', 'activo')->count();

        $totalServicios = DB::table('item_servicio')->count();
        $totalProductos = DB::table('item_producto')->count();

        // ── Items por categoría ───────────────────────────────
        $itemsPorCategoria = DB::table('item')
            ->join('categoria', 'item.id_categoria', '=', 'categoria.id_categoria')
            ->select('categoria.nombre', DB::raw('COUNT(item.id_item) as total'))
            ->groupBy('categoria.nombre')
            ->orderByDesc('total')
            ->get();

        // ── Servicios próximos (fecha_inicio futura) ──────────
        $serviciosProximos = DB::table('item')
            ->join('item_servicio', 'item.id_item', '=', 'item_servicio.id_item')
            ->leftJoin('categoria', 'item.id_categoria', '=', 'categoria.id_categoria')
            ->where('item_servicio.fecha_inicio', '>=', now())
            ->where('item.estado', 'activo')
            ->select(
                'item.id_item',
                'item.nombre',
                'item.precio',
                'item_servicio.fecha_inicio',
                'item_servicio.lugar',
                'item_servicio.cupos_disponibles',
                'item_servicio.cupos_totales',
                'item_servicio.tipo_servicio',
                'categoria.nombre as categoria'
            )
            ->orderBy('item_servicio.fecha_inicio')
            ->limit(5)
            ->get();

        // ── Usuarios recientes ────────────────────────────────
        $usuariosRecientes = DB::table('usuario')
            ->select('id_usuario', 'nombre_completo', 'correo_electronico', 'estado_cuenta', 'fecha_registro')
            ->orderByDesc('fecha_registro')
            ->limit(5)
            ->get();

        // ── Stock bajo (productos con <= 5 unidades) ──────────
        $stockBajo = DB::table('item')
            ->join('item_producto', 'item.id_item', '=', 'item_producto.id_item')
            ->where('item_producto.stock_disponible', '<=', 5)
            ->where('item.estado', '!=', 'inactivo')
            ->select(
                'item.id_item',
                'item.nombre',
                'item_producto.stock_disponible',
                'item_producto.marca'
            )
            ->orderBy('item_producto.stock_disponible')
            ->get();

        return response()->json([
            'usuarios' => [
                'total'        => $usuarios,
                'clientes'     => $clientes,
                'instructores' => $instructores,
                'admins'       => $admins,
            ],
            'items' => [
                'total'     => $totalItems,
                'activos'   => $itemsActivos,
                'agotados'  => $itemsAgotados,
                'inactivos' => $itemsInactivos,
                'servicios' => $totalServicios,
                'productos' => $totalProductos,
            ],
            'categorias' => [
                'total'   => $totalCategorias,
                'activas' => $catActivas,
            ],
            'items_por_categoria' => $itemsPorCategoria,
            'servicios_proximos'  => $serviciosProximos,
            'usuarios_recientes'  => $usuariosRecientes,
            'stock_bajo'          => $stockBajo,
        ]);
    }
}