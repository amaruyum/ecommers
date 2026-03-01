<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemProducto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemProductoController extends Controller
{
    public function index(): JsonResponse { return response()->json(ItemProducto::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_item' => 'required|exists:ITEM,id_item',
            'marca' => 'nullable|string|max:255',
            'fecha_elaboracion' => 'nullable|date',
            'fecha_caducidad' => 'nullable|date',
            'stock_disponible' => 'nullable|integer',
        ]);
        $p = ItemProducto::updateOrCreate(['id_item' => $validated['id_item']], $validated);
        return response()->json($p, 201);
    }

    public function show(string $id): JsonResponse
    {
        $p = ItemProducto::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($p);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $p = ItemProducto::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->update($request->validate([
            'marca' => 'nullable|string|max:255',
            'fecha_elaboracion' => 'nullable|date',
            'fecha_caducidad' => 'nullable|date',
            'stock_disponible' => 'nullable|integer',
        ]));
        return response()->json($p);
    }

    public function destroy(string $id): JsonResponse
    {
        $p = ItemProducto::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
