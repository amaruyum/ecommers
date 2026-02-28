<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Usuario::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'correo_electronico' => 'required|email|unique:USUARIO,correo_electronico',
            'contrasena' => 'required|string',
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'estado_cuenta' => 'nullable|string|max:50',
        ]);
        $validated['estado_cuenta'] = $validated['estado_cuenta'] ?? 'activo';
        $usuario = Usuario::create($validated);
        return response()->json($usuario, 201);
    }

    public function show(string $id): JsonResponse
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        return response()->json($usuario);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        $validated = $request->validate([
            'correo_electronico' => 'sometimes|email|unique:USUARIO,correo_electronico,' . $id . ',id_usuario',
            'contrasena' => 'nullable|string',
            'nombre_completo' => 'sometimes|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'estado_cuenta' => 'nullable|string|max:50',
        ]);
        if (!empty($validated['contrasena'])) {
            $usuario->contrasena = $validated['contrasena'];
        }
        unset($validated['contrasena']);
        $usuario->update($validated);
        return response()->json($usuario);
    }

    public function destroy(string $id): JsonResponse
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        $usuario->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
