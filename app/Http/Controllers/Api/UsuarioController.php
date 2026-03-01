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

    public function login(Request $request): JsonResponse
    {
        $correo = $request->input('correo_electronico', $request->input('email'));
        $contrasena = $request->input('contrasena', $request->input('password'));

        if (!$correo || !$contrasena) {
            return response()->json([
                'message' => 'correo_electronico/email y contrasena/password son obligatorios.',
            ], 422);
        }

        $usuario = Usuario::where('correo_electronico', $correo)->first();

        if (!$usuario || $usuario->contrasena !== $contrasena) {
            return response()->json(['message' => 'Credenciales inválidas.'], 401);
        }

        if (($usuario->estado_cuenta ?? 'activo') !== 'activo') {
            return response()->json(['message' => 'La cuenta no está activa.'], 403);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'usuario' => $usuario,
        ]);
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
