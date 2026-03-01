<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria')->delete();

        $categorias = [
            ['nombre' => 'Yoga & Meditación',      'descripcion' => 'Prácticas de yoga, meditación y mindfulness para el equilibrio mental y físico.',         'tipo' => 'servicio',  'estado' => 'activo'],
            ['nombre' => 'Retiros Espirituales',    'descripcion' => 'Experiencias de retiro en la naturaleza para reconectar con tu ser interior.',             'tipo' => 'servicio',  'estado' => 'activo'],
            ['nombre' => 'Nutrición Holística',     'descripcion' => 'Alimentación consciente, ayurveda y suplementación natural para el bienestar integral.',   'tipo' => 'ambos',     'estado' => 'activo'],
            ['nombre' => 'Fitness & Movimiento',    'descripcion' => 'Entrenamiento funcional, pilates y movimiento consciente para un cuerpo saludable.',       'tipo' => 'servicio',  'estado' => 'activo'],
            ['nombre' => 'Productos Naturales',     'descripcion' => 'Suplementos, aceites esenciales, tés y productos orgánicos para el bienestar diario.',     'tipo' => 'producto',  'estado' => 'activo'],
            ['nombre' => 'Terapias Alternativas',   'descripcion' => 'Aromaterapia, cristaloterapia, reiki y otras terapias complementarias.',                   'tipo' => 'ambos',     'estado' => 'activo'],
            ['nombre' => 'Capacitaciones',          'descripcion' => 'Cursos, talleres y formaciones para instructores y entusiastas del bienestar.',             'tipo' => 'servicio',  'estado' => 'activo'],
            ['nombre' => 'Cuidado Personal',        'descripcion' => 'Productos de higiene natural, cosmética orgánica y cuidado corporal consciente.',          'tipo' => 'producto',  'estado' => 'activo'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categoria')->insert($cat);
        }

        $this->command->info('✅ CategoriaSeeder completado: ' . count($categorias) . ' categorías');
    }
}
