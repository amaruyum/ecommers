<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Categoria::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['nombre' => 'required|string|max:255']);
        $categoria = Categoria::create($validated);
        return response()->json($categoria, 201);
    }

    public function show(string $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($categoria);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['error' => 'No encontrado'], 404);
        $categoria->update($request->validate(['nombre' => 'required|string|max:255']));
        return response()->json($categoria);
    }

    public function destroy(string $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['error' => 'No encontrado'], 404);
        $categoria->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
