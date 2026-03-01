<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('item_servicio')->delete();
        DB::table('item_producto')->delete();
        DB::table('item')->delete();

        // Obtener IDs reales
        $cats = DB::table('categoria')->pluck('id_categoria', 'nombre');
        $instructores = DB::table('usuario_instructor')->pluck('id_instructor');

        $inst = $instructores->values();
        $i0 = $inst[0] ?? 1;
        $i1 = $inst[1] ?? 1;
        $i2 = $inst[2] ?? 1;
        $i3 = $inst[3] ?? 1;

        // ── SERVICIOS ─────────────────────────────────────────
        $servicios = [
            // Retiros
            [
                'item' => [
                    'nombre'       => 'Retiro de Yoga y Silencio en la Montaña',
                    'descripcion'  => 'Un fin de semana de desconexión total en los Andes ecuatorianos. Práctica de yoga al amanecer, meditación guiada, caminatas en la naturaleza y alimentación vegana. Experiencia transformadora para reconectar con tu esencia.',
                    'precio'       => 285.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Retiros Espirituales'] ?? 2,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'retiro',
                    'fecha_inicio'           => now()->addDays(14)->setTime(15, 0),
                    'fecha_fin'              => now()->addDays(16)->setTime(12, 0),
                    'lugar'                  => 'Hacienda El Refugio, Cotacachi',
                    'cupos_totales'          => 20,
                    'cupos_disponibles'      => 14,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "Viernes 15:00 - Llegada y bienvenida\nViernes 17:00 - Práctica de yoga restaurativo\nViernes 19:00 - Cena vegetariana\nSábado 06:00 - Meditación al amanecer\nSábado 08:00 - Desayuno\nSábado 10:00 - Taller de pranayama\nSábado 14:00 - Caminata por los Andes\nSábado 19:00 - Círculo de fuego sagrado\nDomingo 07:00 - Yoga fluido\nDomingo 10:00 - Ceremonia de cierre\nDomingo 12:00 - Despedida",
                    'politicas_cancelacion'  => 'Cancelación gratuita hasta 7 días antes. Entre 7 y 3 días: 50% de reembolso. Menos de 3 días: sin reembolso.',
                    'id_instructor'          => $i0,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Retiro de Meditación Vipassana 3 Días',
                    'descripcion'  => 'Retiro de meditación en noble silencio siguiendo la tradición Vipassana. Tres días de práctica intensiva, introspección profunda y observación de la mente. Incluye hospedaje, alimentación vegetariana y guía especializada.',
                    'precio'       => 320.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Yoga & Meditación'] ?? 1,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'retiro',
                    'fecha_inicio'           => now()->addDays(21)->setTime(18, 0),
                    'fecha_fin'              => now()->addDays(24)->setTime(10, 0),
                    'lugar'                  => 'Centro Ananda, Tumbaco',
                    'cupos_totales'          => 15,
                    'cupos_disponibles'      => 6,
                    'nivel_dificultad'       => 'intermedio',
                    'itinerario'             => "Llegada en silencio el día 1 a las 18:00\nRutina diaria: 05:00 Despertar - 05:30 Meditación - 07:00 Desayuno - 08:00 Meditación - 12:00 Almuerzo - 13:00 Descanso - 14:30 Meditación - 18:00 Cena - 19:00 Discurso del Dhamma - 21:00 Descanso",
                    'politicas_cancelacion'  => 'Sin reembolso una vez iniciado el retiro. Cancelación con 10+ días: reembolso completo menos gastos administrativos.',
                    'id_instructor'          => $i2,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Retiro Ayurvédico en la Costa',
                    'descripcion'  => 'Experiencia de bienestar ayurvédico junto al mar. Incluye diagnóstico de dosha, masajes terapéuticos, alimentación según tu constitución, yoga y sesiones de pranayama. Una semana de transformación total.',
                    'precio'       => 890.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Retiros Espirituales'] ?? 2,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'retiro',
                    'fecha_inicio'           => now()->addDays(35)->setTime(14, 0),
                    'fecha_fin'              => now()->addDays(42)->setTime(11, 0),
                    'lugar'                  => 'Ecolodge Pacífico, Canoa',
                    'cupos_totales'          => 12,
                    'cupos_disponibles'      => 9,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "7 días todo incluido\nMañanas: Yoga y meditación al amanecer\nTardes: Masajes y terapias ayurvédicas\nNoches: Charlas sobre filosofía y bienestar\nAlimentación 100% orgánica según tu dosha",
                    'politicas_cancelacion'  => 'Cancelación con 15+ días: reembolso del 80%. Cancelación con 7-14 días: 50%. Menos de 7 días: sin reembolso.',
                    'id_instructor'          => $i1,
                ],
            ],
            // Capacitaciones
            [
                'item' => [
                    'nombre'       => 'Formación de Instructores de Yoga (200 horas)',
                    'descripcion'  => 'Programa completo de formación certificada para instructores de yoga. Anatomía, filosofía, metodología de enseñanza, pranayama y práctica asana. Certificación avalada internacionalmente por Yoga Alliance.',
                    'precio'       => 1200.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Capacitaciones'] ?? 7,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'capacitacion',
                    'fecha_inicio'           => now()->addDays(30)->setTime(8, 0),
                    'fecha_fin'              => now()->addDays(90)->setTime(17, 0),
                    'lugar'                  => 'Studio Wellness, Quito',
                    'cupos_totales'          => 16,
                    'cupos_disponibles'      => 7,
                    'nivel_dificultad'       => 'intermedio',
                    'itinerario'             => "Módulo 1: Filosofía y Fundamentos del Yoga\nMódulo 2: Anatomía y Fisiología\nMódulo 3: Asanas y Alineación\nMódulo 4: Pranayama y Meditación\nMódulo 5: Metodología de Enseñanza\nMódulo 6: Práctica Supervisada",
                    'politicas_cancelacion'  => '30% no reembolsable como reserva de cupo. El 70% restante es reembolsable hasta 15 días antes del inicio.',
                    'id_instructor'          => $i0,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Taller de Nutrición Consciente y Meal Prep',
                    'descripcion'  => 'Aprende a preparar comidas saludables y balanceadas para toda la semana en pocas horas. Incluye conceptos de macronutrientes, recetas plant-based, técnicas de conservación y planificación semanal.',
                    'precio'       => 65.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Nutrición Holística'] ?? 3,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'taller',
                    'fecha_inicio'           => now()->addDays(7)->setTime(9, 0),
                    'fecha_fin'              => now()->addDays(7)->setTime(14, 0),
                    'lugar'                  => 'Cocina Viva, Quito Norte',
                    'cupos_totales'          => 18,
                    'cupos_disponibles'      => 11,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "09:00 - Bienvenida y conceptos de nutrición\n10:00 - Demostración de técnicas de cocina\n11:00 - Práctica guiada: preparación de 5 comidas\n13:00 - Degustación y preguntas\n14:00 - Cierre y entrega de recetario",
                    'politicas_cancelacion'  => 'Cancelación gratuita hasta 48 horas antes. Sin reembolso en cancelaciones tardías.',
                    'id_instructor'          => $i1,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Clase de Yoga Hatha para Principiantes',
                    'descripcion'  => 'Clase introductoria de yoga Hatha perfecta para quienes inician su camino. Aprenderás posturas básicas, técnicas de respiración y fundamentos de meditación en un ambiente cálido y sin presión.',
                    'precio'       => 18.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Yoga & Meditación'] ?? 1,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'clase',
                    'fecha_inicio'           => now()->addDays(3)->setTime(7, 0),
                    'fecha_fin'              => now()->addDays(3)->setTime(8, 30),
                    'lugar'                  => 'Studio Wellness, Quito',
                    'cupos_totales'          => 20,
                    'cupos_disponibles'      => 13,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "07:00 - Llegada y preparación\n07:10 - Calentamiento y respiración\n07:25 - Práctica de asanas\n08:10 - Relajación (Savasana)\n08:25 - Meditación corta y cierre",
                    'politicas_cancelacion'  => 'Cancelación gratuita hasta 2 horas antes de la clase.',
                    'id_instructor'          => $i0,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Evento: Feria del Bienestar Integral 2026',
                    'descripcion'  => 'El evento más grande de bienestar del año. Ponencias de expertos, clases demostrativas, exposición de marcas naturales, actividades para toda la familia y zona de relajación. ¡Una experiencia única!',
                    'precio'       => 25.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Capacitaciones'] ?? 7,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'evento',
                    'fecha_inicio'           => now()->addDays(45)->setTime(9, 0),
                    'fecha_fin'              => now()->addDays(45)->setTime(19, 0),
                    'lugar'                  => 'Centro de Convenciones Quito',
                    'cupos_totales'          => 500,
                    'cupos_disponibles'      => 312,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "09:00 - Apertura oficial\n10:00 - Conferencia magistral: El futuro del bienestar\n11:30 - Clases demostrativas simultáneas\n13:00 - Almuerzo (zona gastronómica)\n15:00 - Panel de expertos\n17:00 - Actividades y sorteos\n18:30 - Cierre y networking",
                    'politicas_cancelacion'  => 'Las entradas no son reembolsables pero son transferibles.',
                    'id_instructor'          => $i2,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Taller de Meditación y Manejo del Estrés',
                    'descripcion'  => 'Herramientas prácticas y científicamente validadas para reducir el estrés en tu vida cotidiana. Aprenderás técnicas de mindfulness, respiración, visualización y creación de rutinas de bienestar.',
                    'precio'       => 45.00,
                    'tipo'         => 'servicio',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Yoga & Meditación'] ?? 1,
                ],
                'servicio' => [
                    'tipo_servicio'          => 'taller',
                    'fecha_inicio'           => now()->addDays(10)->setTime(18, 0),
                    'fecha_fin'              => now()->addDays(10)->setTime(21, 0),
                    'lugar'                  => 'Online vía Zoom',
                    'cupos_totales'          => 50,
                    'cupos_disponibles'      => 32,
                    'nivel_dificultad'       => 'principiante',
                    'itinerario'             => "18:00 - Introducción al mindfulness\n18:45 - Técnicas de respiración (4-7-8, box breathing)\n19:30 - Meditación guiada\n20:15 - Creación de tu rutina personal\n20:45 - Preguntas y cierre",
                    'politicas_cancelacion'  => 'Se envía la grabación si no puedes asistir en vivo. Sin reembolsos.',
                    'id_instructor'          => $i2,
                ],
            ],
        ];

        // ── PRODUCTOS ─────────────────────────────────────────
        $productos = [
            [
                'item' => [
                    'nombre'       => 'Kit de Aceites Esenciales Relajantes',
                    'descripcion'  => 'Set de 6 aceites esenciales 100% puros: lavanda, eucalipto, menta, naranja dulce, árbol de té y ylang ylang. Perfectos para aromaterapia, masajes y difusor ultrasónico.',
                    'precio'       => 38.50,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Terapias Alternativas'] ?? 6,
                ],
                'producto' => [
                    'marca'               => 'AromaPura',
                    'stock_disponible'    => 24,
                    'fecha_elaboracion'   => now()->subMonths(2)->toDateString(),
                    'fecha_caducidad'     => now()->addYears(2)->toDateString(),
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Colchoneta de Yoga Premium Antideslizante',
                    'descripcion'  => 'Mat de yoga de 6mm de grosor, material TPE ecológico libre de PVC y látex. Superficie antideslizante en ambas caras, marcas de alineación impresas, ligera y duradera. Incluye bolsa de transporte.',
                    'precio'       => 67.00,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Yoga & Meditación'] ?? 1,
                ],
                'producto' => [
                    'marca'               => 'ZenMat Pro',
                    'stock_disponible'    => 15,
                    'fecha_elaboracion'   => now()->subMonths(6)->toDateString(),
                    'fecha_caducidad'     => null,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Proteína Vegana de Cáñamo y Chía',
                    'descripcion'  => 'Suplemento proteico de origen 100% vegetal. 25g de proteína por porción, rico en omega-3, aminoácidos esenciales y fibra. Sin azúcar añadida, sin gluten, sin OGM. Sabor natural neutro, fácil de mezclar.',
                    'precio'       => 32.00,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Nutrición Holística'] ?? 3,
                ],
                'producto' => [
                    'marca'               => 'VerdePower',
                    'stock_disponible'    => 40,
                    'fecha_elaboracion'   => now()->subMonths(1)->toDateString(),
                    'fecha_caducidad'     => now()->addMonths(18)->toDateString(),
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Té Adaptógeno Relax & Sleep',
                    'descripcion'  => 'Mezcla artesanal de hierbas adaptógenas: ashwagandha, valeriana, manzanilla, pasiflora y lavanda. Formulado para reducir el estrés y mejorar la calidad del sueño. 30 sobres por caja.',
                    'precio'       => 18.90,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Productos Naturales'] ?? 5,
                ],
                'producto' => [
                    'marca'               => 'HerbaMística',
                    'stock_disponible'    => 3,
                    'fecha_elaboracion'   => now()->subMonths(3)->toDateString(),
                    'fecha_caducidad'     => now()->addMonths(14)->toDateString(),
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Difusor Ultrasónico de Aromas',
                    'descripcion'  => 'Difusor de aceites esenciales por ultrasonido, capacidad 400ml, temporizador 1/3/6 horas, luz LED de 7 colores regulable, función apagado automático. Ideal para hogar y oficina.',
                    'precio'       => 45.00,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Terapias Alternativas'] ?? 6,
                ],
                'producto' => [
                    'marca'               => 'AromaPura',
                    'stock_disponible'    => 18,
                    'fecha_elaboracion'   => now()->subMonths(4)->toDateString(),
                    'fecha_caducidad'     => null,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Cojín de Meditación Zafu con Relleno de Espelta',
                    'descripcion'  => 'Cojín zafu tradicional para meditación, relleno de cáscara de espelta (buckwheat), funda lavable de algodón orgánico. Altura regulable. Perfecto para largas sesiones de meditación.',
                    'precio'       => 52.00,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Yoga & Meditación'] ?? 1,
                ],
                'producto' => [
                    'marca'               => 'ZenMat Pro',
                    'stock_disponible'    => 0,
                    'fecha_elaboracion'   => now()->subMonths(5)->toDateString(),
                    'fecha_caducidad'     => null,
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Crema Corporal de Karité y Lavanda',
                    'descripcion'  => 'Crema hidratante de lujo con manteca de karité orgánica, aceite de lavanda y vitamina E. Fórmula libre de parabenos, siliconas y petroquímicos. Para piel seca y sensible. 250ml.',
                    'precio'       => 24.50,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Cuidado Personal'] ?? 8,
                ],
                'producto' => [
                    'marca'               => 'PuraNatura',
                    'stock_disponible'    => 5,
                    'fecha_elaboracion'   => now()->subMonths(2)->toDateString(),
                    'fecha_caducidad'     => now()->addMonths(24)->toDateString(),
                ],
            ],
            [
                'item' => [
                    'nombre'       => 'Suplemento de Magnesio Quelato 400mg',
                    'descripcion'  => 'Magnesio bisglicinato quelato de alta absorción para músculos, sueño y sistema nervioso. 90 cápsulas vegetales. Sin aditivos artificiales, apto para veganos.',
                    'precio'       => 27.00,
                    'tipo'         => 'producto',
                    'estado'       => 'activo',
                    'id_categoria' => $cats['Productos Naturales'] ?? 5,
                ],
                'producto' => [
                    'marca'               => 'VerdePower',
                    'stock_disponible'    => 55,
                    'fecha_elaboracion'   => now()->subMonths(1)->toDateString(),
                    'fecha_caducidad'     => now()->addMonths(24)->toDateString(),
                ],
            ],
        ];

        // Insertar servicios
        foreach ($servicios as $data) {
            $itemId = DB::table('item')->insertGetId($data['item'], 'id_item');
            DB::table('item_servicio')->insert(array_merge(
                $data['servicio'],
                ['id_item' => $itemId]
            ));
        }

        // Insertar productos
        foreach ($productos as $data) {
            // Actualizar estado si está agotado
            if ($data['producto']['stock_disponible'] === 0) {
                $data['item']['estado'] = 'agotado';
            }
            $itemId = DB::table('item')->insertGetId($data['item'], 'id_item');
            DB::table('item_producto')->insert(array_merge(
                $data['producto'],
                ['id_item' => $itemId]
            ));
        }

        $this->command->info('✅ ItemSeeder completado: ' . count($servicios) . ' servicios, ' . count($productos) . ' productos');
    }
}