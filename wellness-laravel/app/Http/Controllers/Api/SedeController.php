<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Sede::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);
        $sede = Sede::create($validated);
        return response()->json($sede, 201);
    }

    public function show(string $id): JsonResponse
    {
        $sede = Sede::find($id);
        if (!$sede) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($sede);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $sede = Sede::find($id);
        if (!$sede) return response()->json(['error' => 'No encontrado'], 404);
        $sede->update($request->validate([
            'nombre' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]));
        return response()->json($sede);
    }

    public function destroy(string $id): JsonResponse
    {
        $sede = Sede::find($id);
        if (!$sede) return response()->json(['error' => 'No encontrado'], 404);
        $sede->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
