<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\UsuarioCliente;
use App\Models\UsuarioInstructor;
use App\Models\UsuarioAdministrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * GET /api/usuarios
     */
    public function index(Request $request): JsonResponse
    {
        $query = Usuario::with(['cliente', 'instructor', 'administrador']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_completo', 'ilike', '%' . $request->search . '%')
                  ->orWhere('correo_electronico', 'ilike', '%' . $request->search . '%');
            });
        }

        if ($request->filled('estado_cuenta')) {
            $query->where('estado_cuenta', $request->estado_cuenta);
        }

        if ($request->filled('rol')) {
            if ($request->rol === 'cliente') {
                $query->whereHas('cliente');
            } elseif ($request->rol === 'instructor') {
                $query->whereHas('instructor');
            } elseif ($request->rol === 'admin') {
                $query->whereHas('administrador');
            }
        }

        $usuarios = $query->orderBy('nombre_completo')->get()->map(function ($u) {
            $roles = [];
            if ($u->administrador) $roles[] = 'admin';
            if ($u->instructor)    $roles[] = 'instructor';
            if ($u->cliente)       $roles[] = 'cliente';
            if (empty($roles))     $roles[] = 'usuario';

            return [
                'id_usuario'         => $u->id_usuario,
                'nombre_completo'    => $u->nombre_completo,
                'correo_electronico' => $u->correo_electronico,
                'telefono'           => $u->telefono,
                'estado_cuenta'      => $u->estado_cuenta,
                'fecha_registro'     => $u->fecha_registro,
                'roles'              => $roles,
                'cliente'            => $u->cliente,
                'instructor'         => $u->instructor,
            ];
        });

        return response()->json(['data' => $usuarios, 'total' => $usuarios->count()]);
    }

    /**
     * POST /api/usuarios
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre_completo'    => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuario,correo_electronico',
            'contrasena'         => 'required|string|min:8',
            'telefono'           => 'nullable|string|max:50',
            'estado_cuenta'      => 'required|in:activo,inactivo,suspendido',
            'rol'                => 'required|in:cliente,instructor,admin',
            // Cliente
            'ciudad'             => 'nullable|string',
            'direccion'          => 'nullable|string',
            'preferencias'       => 'nullable|string',
            // Instructor
            'especialidad'          => 'nullable|string',
            'descripcion_perfil'  => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $usuario = Usuario::create([
                'nombre_completo'    => $request->nombre_completo,
                'correo_electronico' => $request->correo_electronico,
                'contrasena'         => Hash::make($request->contrasena),
                'telefono'           => $request->telefono,
                'estado_cuenta'      => $request->estado_cuenta,
            ]);

            if ($request->rol === 'cliente') {
                UsuarioCliente::create([
                    'id_usuario'   => $usuario->id_usuario,
                    'ciudad'       => $request->ciudad,
                    'direccion'    => $request->direccion,
                    'preferencias' => $request->preferencias,
                ]);
            } elseif ($request->rol === 'instructor') {
                UsuarioInstructor::create([
                    'id_usuario'          => $usuario->id_usuario,
                    'especialidad'        => $request->especialidad,
                    'descripcion_perfil' => $request->descripcion_perfil,
                ]);
            } elseif ($request->rol === 'admin') {
                UsuarioAdministrador::create(['id_usuario' => $usuario->id_usuario]);
            }

            DB::commit();
            return response()->json(['message' => 'Usuario creado correctamente', 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/usuarios/{id}
     */
    public function show(int $id): JsonResponse
    {
        $usuario = Usuario::with(['cliente', 'instructor', 'administrador'])->findOrFail($id);
        return response()->json($usuario);
    }

    /**
     * PUT /api/usuarios/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $usuario = Usuario::with(['cliente', 'instructor'])->findOrFail($id);

        $request->validate([
            'nombre_completo'    => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuario,correo_electronico,' . $id . ',id_usuario',
            'telefono'           => 'nullable|string|max:50',
            'estado_cuenta'      => 'required|in:activo,inactivo,suspendido',
            'contrasena'         => 'nullable|string|min:8',
            'ciudad'             => 'nullable|string',
            'direccion'          => 'nullable|string',
            'preferencias'       => 'nullable|string',
            'especialidad'          => 'nullable|string',
            'descripcion_perfil'  => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nombre_completo'    => $request->nombre_completo,
                'correo_electronico' => $request->correo_electronico,
                'telefono'           => $request->telefono,
                'estado_cuenta'      => $request->estado_cuenta,
            ];
            if ($request->filled('contrasena')) {
                $data['contrasena'] = Hash::make($request->contrasena);
            }
            $usuario->update($data);

            if ($usuario->cliente) {
                $usuario->cliente->update([
                    'ciudad'       => $request->ciudad,
                    'direccion'    => $request->direccion,
                    'preferencias' => $request->preferencias,
                ]);
            }

            if ($usuario->instructor) {
                $usuario->instructor->update([
                    'especialidad'        => $request->especialidad,
                    'descripcion_perfil' => $request->descripcion_perfil,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Usuario actualizado correctamente', 'usuario' => $usuario]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/usuarios/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $usuario = Usuario::with(['cliente', 'instructor', 'administrador'])->findOrFail($id);

        // Proteger: no eliminar al propio admin autenticado
        if (auth()->id() === $usuario->id_usuario) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta.'], 422);
        }

        DB::beginTransaction();
        try {
            $usuario->administrador?->delete();
            $usuario->instructor?->delete();
            $usuario->cliente?->delete();
            $usuario->delete();
            DB::commit();
            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}