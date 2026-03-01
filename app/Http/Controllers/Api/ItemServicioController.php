<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemServicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemServicioController extends Controller
{
    public function index(): JsonResponse { return response()->json(ItemServicio::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_item' => 'required|exists:ITEM,id_item',
            'id_instructor' => 'required|exists:USUARIO_INSTRUCTOR,id_instructor',
            'id_sede' => 'required|exists:SEDE,id_sede',
            'tipo_servicio' => 'nullable|string|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'itinerario' => 'nullable|string|max:255',
            'lugar' => 'nullable|string|max:255',
            'cupos_totales' => 'nullable|integer',
            'cupos_disponibles' => 'nullable|integer',
            'politicas_cancelacion' => 'nullable|string|max:255',
        ]);
        $s = ItemServicio::updateOrCreate(['id_item' => $validated['id_item']], $validated);
        return response()->json($s, 201);
    }

    public function show(string $id): JsonResponse
    {
        $s = ItemServicio::find($id);
        if (!$s) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($s);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $s = ItemServicio::find($id);
        if (!$s) return response()->json(['error' => 'No encontrado'], 404);
        $s->update($request->validate([
            'id_instructor' => 'sometimes|exists:USUARIO_INSTRUCTOR,id_instructor',
            'id_sede' => 'sometimes|exists:SEDE,id_sede',
            'tipo_servicio' => 'nullable|string|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'itinerario' => 'nullable|string|max:255',
            'lugar' => 'nullable|string|max:255',
            'cupos_totales' => 'nullable|integer',
            'cupos_disponibles' => 'nullable|integer',
            'politicas_cancelacion' => 'nullable|string|max:255',
        ]));
        return response()->json($s);
    }

    public function destroy(string $id): JsonResponse
    {
        $s = ItemServicio::find($id);
        if (!$s) return response()->json(['error' => 'No encontrado'], 404);
        $s->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
