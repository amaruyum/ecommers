<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario
     * POST /api/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre_completo'    => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuario,correo_electronico',
            'telefono'           => 'nullable|string|max:50',
            'contrasena'         => 'required|string|min:8|confirmed', // requiere contrasena_confirmation
        ]);

        $usuario = Usuario::create([
            'nombre_completo'    => $validated['nombre_completo'],
            'correo_electronico' => $validated['correo_electronico'],
            'telefono'           => $validated['telefono'] ?? null,
            'contrasena'         => Hash::make($validated['contrasena']),
            'estado_cuenta'      => 'activo',
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'usuario' => [
                'id_usuario'         => $usuario->id_usuario,
                'nombre_completo'    => $usuario->nombre_completo,
                'correo_electronico' => $usuario->correo_electronico,
                'estado_cuenta'      => $usuario->estado_cuenta,
            ],
        ], 201);
    }

    /**
     * Login de usuario
     * POST /api/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'contrasena'         => 'required|string',
        ]);

        $usuario = Usuario::where('correo_electronico', $request->correo_electronico)->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        if ($usuario->estado_cuenta !== 'activo') {
            return response()->json([
                'message' => 'Cuenta inactiva. Contacta al administrador.',
            ], 403);
        }

        // Genera token Sanctum
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token'   => $token,
            'usuario' => [
                'id_usuario'         => $usuario->id_usuario,
                'nombre_completo'    => $usuario->nombre_completo,
                'correo_electronico' => $usuario->correo_electronico,
                'estado_cuenta'      => $usuario->estado_cuenta,
            ],
        ]);
    }

    /**
     * Logout — revoca el token actual
     * POST /api/logout  (requiere auth:sanctum)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    /**
     * Devuelve el usuario autenticado
     * GET /api/me  (requiere auth:sanctum)
     */
    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user();

        return response()->json([
            'id_usuario'         => $usuario->id_usuario,
            'nombre_completo'    => $usuario->nombre_completo,
            'correo_electronico' => $usuario->correo_electronico,
            'telefono'           => $usuario->telefono,
            'estado_cuenta'      => $usuario->estado_cuenta,
        ]);
    }
}