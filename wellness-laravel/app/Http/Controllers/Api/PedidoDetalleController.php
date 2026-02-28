<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PedidoDetalle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoDetalleController extends Controller
{
    public function index(): JsonResponse { return response()->json(PedidoDetalle::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_pedido' => 'required|exists:PEDIDO,id_pedido',
            'id_item' => 'required|exists:ITEM,id_item',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);
        $pd = PedidoDetalle::create($validated);
        return response()->json($pd, 201);
    }

    public function show(string $id): JsonResponse
    {
        $pd = PedidoDetalle::find($id);
        if (!$pd) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($pd);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $pd = PedidoDetalle::find($id);
        if (!$pd) return response()->json(['error' => 'No encontrado'], 404);
        $pd->update($request->validate([
            'id_pedido' => 'sometimes|exists:PEDIDO,id_pedido',
            'id_item' => 'sometimes|exists:ITEM,id_item',
            'cantidad' => 'sometimes|integer|min:0',
            'precio' => 'sometimes|numeric',
            'subtotal' => 'sometimes|numeric',
        ]));
        return response()->json($pd);
    }

    public function destroy(string $id): JsonResponse
    {
        $pd = PedidoDetalle::find($id);
        if (!$pd) return response()->json(['error' => 'No encontrado'], 404);
        $pd->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
