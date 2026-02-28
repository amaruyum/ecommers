<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(): JsonResponse { return response()->json(Pago::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_pedido' => 'required|exists:PEDIDO,id_pedido',
            'id_metodo_pago' => 'required|exists:METODO_PAGO,id_metodo_pago',
            'total' => 'nullable|numeric',
            'fecha_pago' => 'nullable|date',
            'estado' => 'nullable|string',
        ]);
        $p = Pago::create($validated);
        return response()->json($p, 201);
    }

    public function show(string $id): JsonResponse
    {
        $p = Pago::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($p);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $p = Pago::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->update($request->validate([
            'id_pedido' => 'sometimes|exists:PEDIDO,id_pedido',
            'id_metodo_pago' => 'sometimes|exists:METODO_PAGO,id_metodo_pago',
            'total' => 'nullable|numeric',
            'fecha_pago' => 'nullable|date',
            'estado' => 'nullable|string',
        ]));
        return response()->json($p);
    }

    public function destroy(string $id): JsonResponse
    {
        $p = Pago::find($id);
        if (!$p) return response()->json(['error' => 'No encontrado'], 404);
        $p->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
