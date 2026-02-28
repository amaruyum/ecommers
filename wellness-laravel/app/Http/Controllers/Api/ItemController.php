<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Item::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_categoria' => 'required|exists:CATEGORIA,id_categoria',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]);
        $item = Item::create($validated);
        return response()->json($item, 201);
    }

    public function show(string $id): JsonResponse
    {
        $item = Item::find($id);
        if (!$item) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($item);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $item = Item::find($id);
        if (!$item) return response()->json(['error' => 'No encontrado'], 404);
        $item->update($request->validate([
            'id_categoria' => 'sometimes|exists:CATEGORIA,id_categoria',
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric',
        ]));
        return response()->json($item);
    }

    public function destroy(string $id): JsonResponse
    {
        $item = Item::find($id);
        if (!$item) return response()->json(['error' => 'No encontrado'], 404);
        $item->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
