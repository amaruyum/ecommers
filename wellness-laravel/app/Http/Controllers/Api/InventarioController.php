<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(): JsonResponse { return response()->json(Inventario::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_item' => 'required|exists:ITEM,id_item',
            'cantidad' => 'required|integer',
            'tipo_movimiento' => 'nullable|string|max:100',
            'fecha_movimiento' => 'nullable|date',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $inv = Inventario::create($validated);
        return response()->json($inv, 201);
    }

    public function show(string $id): JsonResponse
    {
        $inv = Inventario::find($id);
        if (!$inv) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($inv);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $inv = Inventario::find($id);
        if (!$inv) return response()->json(['error' => 'No encontrado'], 404);
        $inv->update($request->validate([
            'id_item' => 'sometimes|exists:ITEM,id_item',
            'cantidad' => 'sometimes|integer',
            'tipo_movimiento' => 'nullable|string|max:100',
            'fecha_movimiento' => 'nullable|date',
            'descripcion' => 'nullable|string|max:255',
        ]));
        return response()->json($inv);
    }

    public function destroy(string $id): JsonResponse
    {
        $inv = Inventario::find($id);
        if (!$inv) return response()->json(['error' => 'No encontrado'], 404);
        $inv->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
