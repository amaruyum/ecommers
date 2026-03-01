<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(): JsonResponse { return response()->json(Pedido::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:USUARIO_CLIENTE,id_usuario',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
            'total_general' => 'nullable|numeric',
        ]);
        $p = Pedido::create($validated);
        return response()->json($p, 201);
    }

    public function show(string $id): JsonResponse
    {
        $p = Pedido::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($p);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $p = Pedido::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->update($request->validate([
            'id_cliente' => 'sometimes|exists:USUARIO_CLIENTE,id_usuario',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
            'total_general' => 'nullable|numeric',
        ]));
        return response()->json($p);
    }

    public function destroy(string $id): JsonResponse
    {
        $p = Pedido::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
