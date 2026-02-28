<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsuarioRol;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioRolController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(UsuarioRol::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'id_rol' => 'required|exists:ROL,id_rol',
        ]);
        UsuarioRol::create($validated);
        return response()->json($validated, 201);
    }

    public function show(Request $request): JsonResponse
    {
        $ur = UsuarioRol::where('id_usuario', $request->id_usuario)->where('id_rol', $request->id_rol)->first();
        if (!$ur) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($ur);
    }

    public function update(Request $request): JsonResponse
    {
        $ur = UsuarioRol::where('id_usuario', $request->id_usuario)->where('id_rol', $request->id_rol)->first();
        if (!$ur) return response()->json(['error' => 'No encontrado'], 404);
        $ur->delete();
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'new_id_rol' => 'required|exists:ROL,id_rol',
        ]);
        UsuarioRol::create(['id_usuario' => $validated['id_usuario'], 'id_rol' => $validated['new_id_rol']]);
        return response()->json(['message' => 'Actualizado']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $ur = UsuarioRol::where('id_usuario', $request->id_usuario)->where('id_rol', $request->id_rol)->first();
        if (!$ur) return response()->json(['error' => 'No encontrado'], 404);
        $ur->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
