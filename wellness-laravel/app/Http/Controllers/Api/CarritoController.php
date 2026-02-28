<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index(): JsonResponse { return response()->json(Carrito::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:USUARIO_CLIENTE,id_usuario',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]);
        $c = Carrito::create($validated);
        return response()->json($c, 201);
    }

    public function show(string $id): JsonResponse
    {
        $c = Carrito::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($c);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $c = Carrito::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->update($request->validate([
            'id_cliente' => 'sometimes|exists:USUARIO_CLIENTE,id_usuario',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]));
        return response()->json($c);
    }

    public function destroy(string $id): JsonResponse
    {
        $c = Carrito::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
