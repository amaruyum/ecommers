<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PuedeTenerCupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PuedeTenerCuponController extends Controller
{
    public function index(): JsonResponse { return response()->json(PuedeTenerCupon::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_cupon' => 'required|exists:CUPON,id_cupon',
            'id_pedido' => 'required|exists:PEDIDO,id_pedido',
        ]);
        $ptc = PuedeTenerCupon::create($validated);
        return response()->json($ptc, 201);
    }

    public function show(string $id): JsonResponse
    {
        $ptc = PuedeTenerCupon::find($id);
        if (!$ptc) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($ptc);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $ptc = PuedeTenerCupon::find($id);
        if (!$ptc) return response()->json(['error' => 'No encontrado'], 404);
        $ptc->update($request->validate([
            'id_cupon' => 'sometimes|exists:CUPON,id_cupon',
            'id_pedido' => 'sometimes|exists:PEDIDO,id_pedido',
        ]));
        return response()->json($ptc);
    }

    public function destroy(string $id): JsonResponse
    {
        $ptc = PuedeTenerCupon::find($id);
        if (!$ptc) return response()->json(['error' => 'No encontrado'], 404);
        $ptc->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
