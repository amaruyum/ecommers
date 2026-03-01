<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    public function index(): JsonResponse { return response()->json(MetodoPago::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['nombre_metodo' => 'required|string|max:100']);
        $mp = MetodoPago::create($validated);
        return response()->json($mp, 201);
    }

    public function show(string $id): JsonResponse
    {
        $mp = MetodoPago::find($id);
        if (!$mp) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($mp);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $mp = MetodoPago::find($id);
        if (!$mp) return response()->json(['error' => 'No encontrado'], 404);
        $mp->update($request->validate(['nombre_metodo' => 'required|string|max:100']));
        return response()->json($mp);
    }

    public function destroy(string $id): JsonResponse
    {
        $mp = MetodoPago::find($id);
        if (!$mp) return response()->json(['error' => 'No encontrado'], 404);
        $mp->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
