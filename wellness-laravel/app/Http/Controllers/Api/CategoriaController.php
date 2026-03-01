<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * GET /api/categorias
     */
    public function index(Request $request): JsonResponse
    {
        $query = Categoria::query();

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo !== '') {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por nombre
        if ($request->has('search') && $request->search !== '') {
            $query->where('nombre', 'ilike', '%' . $request->search . '%');
        }

        $categorias = $query->orderBy('nombre')->get();

        return response()->json([
            'data'  => $categorias,
            'total' => $categorias->count(),
        ]);
    }

    /**
     * POST /api/categorias
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:150|unique:categoria,nombre',
            'descripcion' => 'nullable|string',
            'tipo'        => 'required|in:producto,servicio,mixto',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json([
            'message'   => 'Categoría creada correctamente',
            'categoria' => $categoria,
        ], 201);
    }

    /**
     * GET /api/categorias/{id}
     */
    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::with('items')->findOrFail($id);

        return response()->json($categoria);
    }

    /**
     * PUT /api/categorias/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre'      => 'required|string|max:150|unique:categoria,nombre,' . $id . ',id_categoria',
            'descripcion' => 'nullable|string',
            'tipo'        => 'required|in:producto,servicio,mixto',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        $categoria->update($validated);

        return response()->json([
            'message'   => 'Categoría actualizada correctamente',
            'categoria' => $categoria,
        ]);
    }

    /**
     * DELETE /api/categorias/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);

        // Verificar si tiene items asociados
        if ($categoria->items()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la categoría porque tiene items asociados.',
            ], 422);
        }

        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente',
        ]);
    }
}