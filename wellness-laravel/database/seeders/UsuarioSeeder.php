<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // ── Limpiar tablas en orden (FK) ──────────────────────
        DB::table('usuario_administrador')->delete();
        DB::table('usuario_instructor')->delete();
        DB::table('usuario_cliente')->delete();
        DB::table('usuario')->delete();

        // ── 1. Administrador ──────────────────────────────────
        $adminId = DB::table('usuario')->insertGetId([
            'correo_electronico' => 'admin@wellness.com',
            'contrasena'         => Hash::make('password123'),
            'nombre_completo'    => 'Admin Wellness',
            'telefono'           => '+593 99 000 0001',
            'estado_cuenta'      => 'activo',
            'fecha_registro'     => now()->subDays(60),
            'fecha_modificacion' => now(),
        ], 'id_usuario');
        DB::table('usuario_administrador')->insert(['id_usuario' => $adminId]);

        // ── 2. Instructores ───────────────────────────────────
        $instructores = [
            [
                'correo_electronico' => 'maria.paz@wellness.com',
                'nombre_completo'    => 'María Paz Villalba',
                'telefono'           => '+593 98 111 2233',
                'especialidad'       => 'Yoga Hatha y Meditación Mindfulness',
                'descripcion_perfil' => 'Instructora certificada con 10 años de experiencia en yoga y meditación. Formada en India y especializada en técnicas de respiración pranayama.',
            ],
            [
                'correo_electronico' => 'carlos.rios@wellness.com',
                'nombre_completo'    => 'Carlos Ríos Mendoza',
                'telefono'           => '+593 98 222 3344',
                'especialidad'       => 'Nutrición Holística y Ayurveda',
                'descripcion_perfil' => 'Nutricionista holístico y terapeuta ayurvédico. Más de 8 años ayudando a personas a reconectar con su bienestar a través de la alimentación consciente.',
            ],
            [
                'correo_electronico' => 'sofia.luna@wellness.com',
                'nombre_completo'    => 'Sofía Luna Espinoza',
                'telefono'           => '+593 98 333 4455',
                'especialidad'       => 'Retiros de Silencio y Meditación Vipassana',
                'descripcion_perfil' => 'Guía de retiros de silencio y practicante de meditación Vipassana desde 2012. Ha dirigido más de 50 retiros en Ecuador, Perú y Colombia.',
            ],
            [
                'correo_electronico' => 'andres.mora@wellness.com',
                'nombre_completo'    => 'Andrés Mora Quintero',
                'telefono'           => '+593 98 444 5566',
                'especialidad'       => 'Fitness Funcional y Bienestar Físico',
                'descripcion_perfil' => 'Entrenador personal certificado, especialista en movimiento funcional y bienestar físico integral. Combina el ejercicio con prácticas de mindfulness.',
            ],
        ];

        foreach ($instructores as $data) {
            $id = DB::table('usuario')->insertGetId([
                'correo_electronico' => $data['correo_electronico'],
                'contrasena'         => Hash::make('password123'),
                'nombre_completo'    => $data['nombre_completo'],
                'telefono'           => $data['telefono'],
                'estado_cuenta'      => 'activo',
                'fecha_registro'     => now()->subDays(rand(30, 90)),
                'fecha_modificacion' => now(),
            ], 'id_usuario');
            DB::table('usuario_instructor')->insert([
                'id_usuario'      => $id,
                'especialidad'    => $data['especialidad'],
                'descripcion_perfil' => $data['descripcion_perfil'],
            ]);
        }

        // ── 3. Clientes ───────────────────────────────────────
        $clientes = [
            ['nombre_completo' => 'Ana Lucía Torres',   'correo' => 'ana.torres@email.com',   'ciudad' => 'Quito',     'preferencias' => 'Yoga, Meditación, Retiros de naturaleza'],
            ['nombre_completo' => 'Roberto Vega',       'correo' => 'roberto.vega@email.com', 'ciudad' => 'Guayaquil', 'preferencias' => 'Nutrición, Fitness, Talleres'],
            ['nombre_completo' => 'Camila Herrera',     'correo' => 'camila.h@email.com',     'ciudad' => 'Cuenca',    'preferencias' => 'Retiros de silencio, Meditación'],
            ['nombre_completo' => 'Diego Morales',      'correo' => 'diego.m@email.com',      'ciudad' => 'Quito',     'preferencias' => 'Yoga, Fitness funcional'],
            ['nombre_completo' => 'Valentina Ruiz',     'correo' => 'vale.ruiz@email.com',    'ciudad' => 'Ambato',    'preferencias' => 'Capacitaciones, Nutrición holística'],
            ['nombre_completo' => 'Sebastián Castro',   'correo' => 'seb.castro@email.com',   'ciudad' => 'Loja',      'preferencias' => 'Retiros, Trekking, Meditación'],
            ['nombre_completo' => 'Gabriela Flores',    'correo' => 'gaby.flores@email.com',  'ciudad' => 'Quito',     'preferencias' => 'Yoga prenatal, Meditación'],
            ['nombre_completo' => 'Javier Ortega',      'correo' => 'javier.o@email.com',     'ciudad' => 'Riobamba',  'preferencias' => 'Fitness, Nutrición deportiva'],
        ];

        foreach ($clientes as $c) {
            $id = DB::table('usuario')->insertGetId([
                'correo_electronico' => $c['correo'],
                'contrasena'         => Hash::make('password123'),
                'nombre_completo'    => $c['nombre_completo'],
                'telefono'           => '+593 9' . rand(1, 9) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'estado_cuenta'      => 'activo',
                'fecha_registro'     => now()->subDays(rand(1, 120)),
                'fecha_modificacion' => now(),
            ], 'id_usuario');
            DB::table('usuario_cliente')->insert([
                'id_usuario'   => $id,
                'ciudad'       => $c['ciudad'],
                'direccion'    => 'Av. Principal ' . rand(100, 999) . ', ' . $c['ciudad'],
                'preferencias' => $c['preferencias'],
            ]);
        }

        $this->command->info('✅ UsuarioSeeder completado: 1 admin, 4 instructores, 8 clientes');
    }
}