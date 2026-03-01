<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarritoItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarritoItemController extends Controller
{
    public function index(): JsonResponse { return response()->json(CarritoItem::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_carrito' => 'required|exists:CARRITO,id_carrito',
            'id_item' => 'required|exists:ITEM,id_item',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);
        $ci = CarritoItem::create($validated);
        return response()->json($ci, 201);
    }

    public function show(string $id): JsonResponse
    {
        $ci = CarritoItem::find($id);
        if (!$ci) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($ci);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $ci = CarritoItem::find($id);
        if (!$ci) return response()->json(['error' => 'No encontrado'], 404);
        $ci->update($request->validate([
            'id_carrito' => 'sometimes|exists:CARRITO,id_carrito',
            'id_item' => 'sometimes|exists:ITEM,id_item',
            'cantidad' => 'sometimes|integer|min:0',
            'precio' => 'sometimes|numeric',
            'subtotal' => 'sometimes|numeric',
        ]));
        return response()->json($ci);
    }

    public function destroy(string $id): JsonResponse
    {
        $ci = CarritoItem::find($id);
        if (!$ci) return response()->json(['error' => 'No encontrado'], 404);
        $ci->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
