<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contenido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContenidoController extends Controller
{
    public function index(): JsonResponse { return response()->json(Contenido::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_administrador' => 'required|exists:USUARIO_ADMINISTRADOR,id_usuario',
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'nullable|string|max:255',
            'tipo_contenido' => 'nullable|string|max:100',
        ]);
        $c = Contenido::create($validated);
        return response()->json($c, 201);
    }

    public function show(string $id): JsonResponse
    {
        $c = Contenido::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($c);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $c = Contenido::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->update($request->validate([
            'id_administrador' => 'sometimes|exists:USUARIO_ADMINISTRADOR,id_usuario',
            'titulo' => 'sometimes|string|max:255',
            'cuerpo' => 'nullable|string|max:255',
            'tipo_contenido' => 'nullable|string|max:100',
        ]));
        return response()->json($c);
    }

    public function destroy(string $id): JsonResponse
    {
        $c = Contenido::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
