<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contenido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContenidoController extends Controller
{
    /**
     * GET /api/contenido
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contenido::query();

        if ($request->filled('tipo_contenido')) {
            $query->where('tipo_contenido', $request->tipo_contenido);
        }

        if ($request->filled('search')) {
            $query->where('titulo', 'ilike', '%' . $request->search . '%');
        }

        $contenidos = $query->orderByDesc('fecha_creacion')->get();

        return response()->json([
            'data'  => $contenidos,
            'total' => $contenidos->count(),
        ]);
    }

    /**
     * POST /api/contenido
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo'          => 'required|string',
            'cuerpo'          => 'required|string',
            'tipo_contenido'  => 'required|in:articulo,noticia,video,anuncio',
        ]);

        $validated['id_administrador'] = auth()->id();
        $validated['fecha_creacion']   = now();

        $contenido = Contenido::create($validated);

        return response()->json([
            'message'   => 'Contenido creado correctamente',
            'contenido' => $contenido,
        ], 201);
    }

    /**
     * GET /api/contenido/{id}
     */
    public function show(int $id): JsonResponse
    {
        $contenido = Contenido::findOrFail($id);
        return response()->json($contenido);
    }

    /**
     * PUT /api/contenido/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $contenido = Contenido::findOrFail($id);

        $validated = $request->validate([
            'titulo'         => 'required|string',
            'cuerpo'         => 'required|string',
            'tipo_contenido' => 'required|in:articulo,noticia,video,anuncio',
        ]);

        $contenido->update($validated);

        return response()->json([
            'message'   => 'Contenido actualizado correctamente',
            'contenido' => $contenido,
        ]);
    }

    /**
     * DELETE /api/contenido/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        Contenido::findOrFail($id)->delete();
        return response()->json(['message' => 'Contenido eliminado correctamente']);
    }
}