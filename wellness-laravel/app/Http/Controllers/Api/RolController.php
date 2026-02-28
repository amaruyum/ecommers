<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Rol::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['nombre_rol' => 'required|string|max:100']);
        $rol = Rol::create($validated);
        return response()->json($rol, 201);
    }

    public function show(string $id): JsonResponse
    {
        $rol = Rol::find($id);
        if (!$rol) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($rol);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $rol = Rol::find($id);
        if (!$rol) return response()->json(['error' => 'No encontrado'], 404);
        $rol->update($request->validate(['nombre_rol' => 'required|string|max:100']));
        return response()->json($rol);
    }

    public function destroy(string $id): JsonResponse
    {
        $rol = Rol::find($id);
        if (!$rol) return response()->json(['error' => 'No encontrado'], 404);
        $rol->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
