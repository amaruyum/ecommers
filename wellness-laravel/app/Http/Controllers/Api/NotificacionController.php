<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(): JsonResponse { return response()->json(Notificacion::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'tipo' => 'nullable|string|max:100',
            'contenido_mensaje' => 'nullable|string|max:255',
            'fecha_envio' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]);
        $n = Notificacion::create($validated);
        return response()->json($n, 201);
    }

    public function show(string $id): JsonResponse
    {
        $n = Notificacion::find($id);
        if (!$n) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($n);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $n = Notificacion::find($id);
        if (!$n) return response()->json(['error' => 'No encontrado'], 404);
        $n->update($request->validate([
            'id_usuario' => 'sometimes|exists:USUARIO,id_usuario',
            'tipo' => 'nullable|string|max:100',
            'contenido_mensaje' => 'nullable|string|max:255',
            'fecha_envio' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]));
        return response()->json($n);
    }

    public function destroy(string $id): JsonResponse
    {
        $n = Notificacion::find($id);
        if (!$n) return response()->json(['error' => 'No encontrado'], 404);
        $n->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
