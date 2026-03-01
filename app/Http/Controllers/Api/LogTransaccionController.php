<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogTransaccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogTransaccionController extends Controller
{
    public function index(): JsonResponse { return response()->json(LogTransaccion::all()); }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:USUARIO,id_usuario',
            'id_pedido' => 'nullable|exists:PEDIDO,id_pedido',
            'id_pago' => 'nullable|exists:PAGO,id_pago',
        ]);
        $log = LogTransaccion::create($validated);
        return response()->json($log, 201);
    }

    public function show(string $id): JsonResponse
    {
        $log = LogTransaccion::find($id);
        if (!$log) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($log);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $log = LogTransaccion::find($id);
        if (!$log) return response()->json(['error' => 'No encontrado'], 404);
        $log->update($request->validate([
            'id_usuario' => 'sometimes|exists:USUARIO,id_usuario',
            'id_pedido' => 'nullable|exists:PEDIDO,id_pedido',
            'id_pago' => 'nullable|exists:PAGO,id_pago',
        ]));
        return response()->json($log);
    }

    public function destroy(string $id): JsonResponse
    {
        $log = LogTransaccion::find($id);
        if (!$log) return response()->json(['error' => 'No encontrado'], 404);
        $log->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
