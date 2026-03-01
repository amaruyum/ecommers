<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsuarioCliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioClienteController extends Controller
{
    public function index(): JsonResponse { return response()->json(UsuarioCliente::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'preferencias' => 'nullable|string|max:255',
        ]);
        $c = UsuarioCliente::updateOrCreate(['id_usuario' => $validated['id_usuario']], $validated);
        return response()->json($c, 201);
    }

    public function show(string $id): JsonResponse
    {
        $c = UsuarioCliente::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($c);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $c = UsuarioCliente::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->update($request->validate([
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'preferencias' => 'nullable|string|max:255',
        ]));
        return response()->json($c);
    }

    public function destroy(string $id): JsonResponse
    {
        $c = UsuarioCliente::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
