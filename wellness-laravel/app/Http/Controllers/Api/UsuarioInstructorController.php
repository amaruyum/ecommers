<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsuarioInstructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioInstructorController extends Controller
{
    public function index(): JsonResponse { return response()->json(UsuarioInstructor::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'descripcion_perfil' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
        ]);
        $i = UsuarioInstructor::create($validated);
        return response()->json($i, 201);
    }

    public function show(string $id): JsonResponse
    {
        $i = UsuarioInstructor::find($id);
        if (!$i) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($i);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $i = UsuarioInstructor::find($id);
        if (!$i) return response()->json(['error' => 'No encontrado'], 404);
        $i->update($request->validate([
            'id_usuario' => 'sometimes|exists:USUARIO,id_usuario',
            'descripcion_perfil' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
        ]));
        return response()->json($i);
    }

    public function destroy(string $id): JsonResponse
    {
        $i = UsuarioInstructor::find($id);
        if (!$i) return response()->json(['error' => 'No encontrado'], 404);
        $i->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
