<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemProducto;
use App\Models\ItemServicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * GET /api/items
     */
    public function index(Request $request): JsonResponse
    {
        $query = Item::with(['categoria', 'producto', 'servicio']);

        if ($request->filled('search')) {
            $query->where('nombre', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->filled('tipo')) {
            if ($request->tipo === 'producto') {
                $query->whereHas('producto');
            } elseif ($request->tipo === 'servicio') {
                $query->whereHas('servicio');
            }
        }

        $items = $query->orderBy('nombre')->get()->map(function ($item) {
            $tipo = $item->producto ? 'producto' : ($item->servicio ? 'servicio' : 'item');
            return array_merge($item->toArray(), ['tipo' => $tipo]);
        });

        return response()->json([
            'data'  => $items,
            'total' => $items->count(),
        ]);
    }

    /**
     * POST /api/items
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tipo'         => 'required|in:producto,servicio',
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'estado'       => 'required|in:activo,inactivo,agotado',
            'precio'       => 'required|numeric|min:0',
            // Producto
            'marca'              => 'required_if:tipo,producto|nullable|string',
            'fecha_elaboracion'  => 'nullable|date',
            'fecha_caducidad'    => 'nullable|date|after_or_equal:fecha_elaboracion',
            'stock_disponible'   => 'required_if:tipo,producto|nullable|integer|min:0',
            // Servicio
            'id_instructor'         => 'nullable|integer',
            'id_sede'               => 'nullable|integer',
            'tipo_servicio'         => 'required_if:tipo,servicio|nullable|string',
            'fecha_inicio'          => 'required_if:tipo,servicio|nullable|date',
            'fecha_fin'             => 'nullable|date|after_or_equal:fecha_inicio',
            'itinerario'            => 'nullable|string',
            'lugar'                 => 'nullable|string',
            'cupos_totales'         => 'required_if:tipo,servicio|nullable|integer|min:1',
            'cupos_disponibles'     => 'nullable|integer|min:0',
            'politicas_cancelacion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::create([
                'id_categoria' => $request->id_categoria,
                'nombre'       => $request->nombre,
                'descripcion'  => $request->descripcion,
                'estado'       => $request->estado,
                'precio'       => $request->precio,
            ]);

            if ($request->tipo === 'producto') {
                ItemProducto::create([
                    'id_item'           => $item->id_item,
                    'marca'             => $request->marca,
                    'fecha_elaboracion' => $request->fecha_elaboracion,
                    'fecha_caducidad'   => $request->fecha_caducidad,
                    'stock_disponible'  => $request->stock_disponible ?? 0,
                ]);
            } else {
                ItemServicio::create([
                    'id_item'               => $item->id_item,
                    'id_instructor'         => $request->id_instructor,
                    'id_sede'               => $request->id_sede,
                    'tipo_servicio'         => $request->tipo_servicio,
                    'fecha_inicio'          => $request->fecha_inicio,
                    'fecha_fin'             => $request->fecha_fin,
                    'itinerario'            => $request->itinerario,
                    'lugar'                 => $request->lugar,
                    'cupos_totales'         => $request->cupos_totales,
                    'cupos_disponibles'     => $request->cupos_disponibles ?? $request->cupos_totales,
                    'politicas_cancelacion' => $request->politicas_cancelacion,
                ]);
            }

            DB::commit();
            $item->load(['categoria', 'producto', 'servicio']);

            return response()->json([
                'message' => 'Item creado correctamente',
                'item'    => $item,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/items/{id}
     */
    public function show(int $id): JsonResponse
    {
        $item = Item::with(['categoria', 'producto', 'servicio'])->findOrFail($id);
        $tipo = $item->producto ? 'producto' : ($item->servicio ? 'servicio' : 'item');

        return response()->json(array_merge($item->toArray(), ['tipo' => $tipo]));
    }

    /**
     * PUT /api/items/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $item = Item::with(['producto', 'servicio'])->findOrFail($id);
        $tipo = $item->producto ? 'producto' : 'servicio';

        $request->validate([
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'estado'       => 'required|in:activo,inactivo,agotado',
            'precio'       => 'required|numeric|min:0',
            'marca'             => 'nullable|string',
            'fecha_elaboracion' => 'nullable|date',
            'fecha_caducidad'   => 'nullable|date',
            'stock_disponible'  => 'nullable|integer|min:0',
            'tipo_servicio'         => 'nullable|string',
            'fecha_inicio'          => 'nullable|date',
            'fecha_fin'             => 'nullable|date',
            'itinerario'            => 'nullable|string',
            'lugar'                 => 'nullable|string',
            'cupos_totales'         => 'nullable|integer|min:1',
            'cupos_disponibles'     => 'nullable|integer|min:0',
            'politicas_cancelacion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item->update([
                'id_categoria' => $request->id_categoria,
                'nombre'       => $request->nombre,
                'descripcion'  => $request->descripcion,
                'estado'       => $request->estado,
                'precio'       => $request->precio,
            ]);

            if ($tipo === 'producto' && $item->producto) {
                $item->producto->update([
                    'marca'             => $request->marca,
                    'fecha_elaboracion' => $request->fecha_elaboracion,
                    'fecha_caducidad'   => $request->fecha_caducidad,
                    'stock_disponible'  => $request->stock_disponible ?? 0,
                ]);
            } elseif ($tipo === 'servicio' && $item->servicio) {
                $item->servicio->update([
                    'tipo_servicio'         => $request->tipo_servicio,
                    'fecha_inicio'          => $request->fecha_inicio,
                    'fecha_fin'             => $request->fecha_fin,
                    'itinerario'            => $request->itinerario,
                    'lugar'                 => $request->lugar,
                    'cupos_totales'         => $request->cupos_totales,
                    'cupos_disponibles'     => $request->cupos_disponibles,
                    'politicas_cancelacion' => $request->politicas_cancelacion,
                ]);
            }

            DB::commit();
            $item->load(['categoria', 'producto', 'servicio']);

            return response()->json([
                'message' => 'Item actualizado correctamente',
                'item'    => $item,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/items/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $item = Item::with(['producto', 'servicio'])->findOrFail($id);

        DB::beginTransaction();
        try {
            $item->producto?->delete();
            $item->servicio?->delete();
            $item->delete();
            DB::commit();

            return response()->json(['message' => 'Item eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}