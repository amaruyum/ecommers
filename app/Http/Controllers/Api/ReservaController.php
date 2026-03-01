<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(): JsonResponse { return response()->json(Reserva::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:USUARIO_CLIENTE,id_usuario',
            'id_servicio' => 'required|exists:ITEM_SERVICIO,id_item',
            'fecha_reserva' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]);
        $r = Reserva::create($validated);
        return response()->json($r, 201);
    }

    public function show(string $id): JsonResponse
    {
        $r = Reserva::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($r);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $r = Reserva::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        $r->update($request->validate([
            'id_cliente' => 'sometimes|exists:USUARIO_CLIENTE,id_usuario',
            'id_servicio' => 'sometimes|exists:ITEM_SERVICIO,id_item',
            'fecha_reserva' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]));
        return response()->json($r);
    }

    public function destroy(string $id): JsonResponse
    {
        $r = Reserva::find($id);
        if (!$r) return response()->json(['error' => 'No encontrado'], 404);
        $r->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
