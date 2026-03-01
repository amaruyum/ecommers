<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CuponController extends Controller
{
    public function index(): JsonResponse { return response()->json(Cupon::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:100|unique:CUPON,codigo',
            'descripcion' => 'nullable|string|max:255',
            'valor_descuento' => 'nullable|numeric',
            'fecha_expiracion' => 'nullable|date',
        ]);
        $c = Cupon::create($validated);
        return response()->json($c, 201);
    }

    public function show(string $id): JsonResponse
    {
        $c = Cupon::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($c);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $c = Cupon::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->update($request->validate([
            'codigo' => 'sometimes|string|max:100|unique:CUPON,codigo,' . $id . ',id_cupon',
            'descripcion' => 'nullable|string|max:255',
            'valor_descuento' => 'nullable|numeric',
            'fecha_expiracion' => 'nullable|date',
        ]));
        return response()->json($c);
    }

    public function destroy(string $id): JsonResponse
    {
        $c = Cupon::find($id);
        if (!$c) return response()->json(['error' => 'No encontrado'], 404);
        $c->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
