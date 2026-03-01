<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsuarioAdministrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioAdministradorController extends Controller
{
    public function index(): JsonResponse { return response()->json(UsuarioAdministrador::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['id_usuario' => 'required|exists:USUARIO,id_usuario']);
        $a = UsuarioAdministrador::firstOrCreate($validated);
        return response()->json($a, 201);
    }

    public function show(string $id): JsonResponse
    {
        $a = UsuarioAdministrador::find($id);
        if (!$a) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($a);
    }

    public function destroy(string $id): JsonResponse
    {
        $a = UsuarioAdministrador::find($id);
        if (!$a) return response()->json(['error' => 'No encontrado'], 404);
        $a->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
