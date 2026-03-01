<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function index(): JsonResponse { return response()->json(Resena::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:USUARIO_CLIENTE,id_usuario',
            'id_item' => 'required|exists:ITEM,id_item',
            'puntuacion' => 'nullable|integer',
            'comentario' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);
        $r = Resena::create($validated);
        return response()->json($r, 201);
    }

    public function show(string $id): JsonResponse
    {
        $r = Resena::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($r);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $r = Resena::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        $r->update($request->validate([
            'id_cliente' => 'sometimes|exists:USUARIO_CLIENTE,id_usuario',
            'id_item' => 'sometimes|exists:ITEM,id_item',
            'puntuacion' => 'nullable|integer',
            'comentario' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]));
        return response()->json($r);
    }

    public function destroy(string $id): JsonResponse
    {
        $r = Resena::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        $r->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
