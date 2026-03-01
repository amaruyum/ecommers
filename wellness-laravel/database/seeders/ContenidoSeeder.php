<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContenidoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contenido')->delete();

        $adminId = DB::table('usuario_administrador')->value('id_administrador');

        $contenidos = [
            [
                'titulo'         => '5 Razones para Comenzar a Meditar Hoy',
                'tipo_contenido' => 'articulo',
                'fecha_creacion' => now()->subDays(2),
                'cuerpo'         => "La meditación ha dejado de ser una práctica exclusiva de monjes y gurús para convertirse en una herramienta cotidiana de bienestar respaldada por la ciencia. Aquí te contamos por qué deberías empezar hoy.

1. REDUCE EL ESTRÉS Y LA ANSIEDAD
Numerosos estudios demuestran que la meditación reduce significativamente los niveles de cortisol, la hormona del estrés. Con solo 10 minutos diarios, el sistema nervioso empieza a regularse de manera notable.

2. MEJORA LA CALIDAD DEL SUEÑO
Practicar meditación antes de dormir prepara al cerebro para entrar en estados de ondas delta, propios del sueño profundo. Las personas que meditan reportan despertar más descansadas y con menos insomnio.

3. AUMENTA LA CONCENTRACIÓN Y PRODUCTIVIDAD
La práctica constante de mindfulness fortalece el córtex prefrontal, la zona del cerebro encargada de la atención sostenida. Muchos ejecutivos de Silicon Valley meditan precisamente por este motivo.

4. FORTALECE EL SISTEMA INMUNE
Investigadores de Harvard demostraron que la meditación activa genes relacionados con la respuesta inmune y reduce marcadores de inflamación crónica. Tu cuerpo literalmente se cura mejor cuando meditas.

5. GENERA MAYOR AUTOCONOCIMIENTO
Al observar tus pensamientos sin identificarte con ellos, desarrollas una relación más sana contigo mismo. Entiendes tus patrones, tus emociones y puedes responder en vez de reaccionar ante las situaciones de la vida.

¿Por dónde empezar? Te recomendamos comenzar con 5 minutos diarios de respiración consciente. Pon un temporizador, cierra los ojos, y simplemente observa tu respiración. Sin juzgar. Sin expectativas. Solo siendo.",
            ],
            [
                'titulo'         => 'Alimentación Consciente: Cómo Transformar Tu Relación con la Comida',
                'tipo_contenido' => 'articulo',
                'fecha_creacion' => now()->subDays(5),
                'cuerpo'         => "En un mundo de comida rápida, apps de delivery y pantallas durante las comidas, hemos perdido algo fundamental: la conexión con lo que comemos. La alimentación consciente (mindful eating) propone recuperar esa relación.

¿QUÉ ES EL MINDFUL EATING?
No es una dieta. No tiene lista de alimentos prohibidos. Es una práctica de atención plena aplicada al acto de comer: observar los sabores, las texturas, el hambre real versus el emocional, y las señales de saciedad de tu cuerpo.

PRINCIPIOS BÁSICOS

Come sin distracciones. Apaga la televisión, deja el celular. La comida merece tu atención completa.

Mastica despacio. Se recomienda entre 20 y 30 masticaciones por bocado. Parece exagerado, pero transforma completamente la experiencia.

Identifica el hambre real. Antes de comer, pregúntate: ¿Tengo hambre o estoy aburrido/estresado/ansioso? Aprender a distinguirlos es liberador.

Respeta la saciedad. El cerebro tarda 20 minutos en registrar que el estómago está lleno. Come lento y para cuando te sientas satisfecho, no lleno.

Elige alimentos que nutran y disfruten. No se trata de comer solo lechuga. Se trata de que cuando comas pizza, la disfrutes plenamente sin culpa.

UN EJERCICIO PARA EMPEZAR
La próxima vez que tengas una comida, tómate 3 respiraciones profundas antes de comenzar. Observa los colores, el aroma. Con el primer bocado, cierra los ojos. ¿Qué texturas sientes? ¿Qué sabores aparecen primero y cuáles después?

Este simple ejercicio puede cambiar tu relación con la comida para siempre.",
            ],
            [
                'titulo'         => 'Video: Descubre el Poder del Bienestar Integral',
                'tipo_contenido' => 'video',
                'fecha_creacion' => now()->subDays(1),
                'cuerpo'         => "Compartimos con ustedes este poderoso video que resume nuestra filosofía de bienestar integral: la unión armónica de mente, cuerpo y espíritu.

En este material encontrarás una introducción profunda a los principios que guían todas nuestras actividades, retiros y productos. Una invitación a ver el bienestar no como un destino sino como un camino que se recorre cada día, con cada decisión, con cada respiración.

Este video es el punto de partida perfecto para quienes nos conocen por primera vez y para quienes ya forman parte de nuestra comunidad y desean renovar su compromiso con su propio bienestar.

Míralo con atención, con el corazón abierto, y escribe en los comentarios qué es lo que más resuena contigo.",
            ],
            [
                'titulo'         => 'Los Beneficios del Yoga que la Ciencia ya Comprobó',
                'tipo_contenido' => 'articulo',
                'fecha_creacion' => now()->subDays(8),
                'cuerpo'         => "Durante décadas el yoga fue visto con escepticismo por la medicina occidental. Hoy, con cientos de estudios publicados en revistas como JAMA y The Lancet, la ciencia ha validado lo que practicantes de miles de años ya sabían.

BENEFICIOS FÍSICOS COMPROBADOS

Flexibilidad y movilidad articular: Un estudio de la Universidad de Ohio demostró que 8 semanas de yoga aumentan la flexibilidad hasta un 35% y reducen el dolor articular en personas con artritis.

Fuerza muscular funcional: El yoga desarrolla fuerza de manera equlibrada, trabajando músculos estabilizadores que el gimnasio convencional ignora.

Salud cardiovascular: Practicar yoga 3 veces por semana reduce la presión arterial sistólica hasta 5 puntos y mejora la variabilidad del ritmo cardíaco.

BENEFICIOS MENTALES Y EMOCIONALES

La práctica de yoga aumenta los niveles de GABA (ácido gamma-aminobutírico) en el cerebro, el mismo neurotransmisor que regulan muchos ansiolíticos, pero de forma natural.

Un metaanálisis de 25 estudios encontró que el yoga reduce síntomas de depresión de forma comparable a los antidepresivos en casos leves a moderados.

¿QUÉ ESTILO ELEGIR?
Para estrés y ansiedad: Yin yoga o yoga restaurativo. Para fuerza y energía: Ashtanga o Power yoga. Para principiantes: Hatha yoga o yoga terapéutico. Para espiritualidad: Kundalini yoga.",
            ],
            [
                'titulo'         => '¡Nuevas Fechas de Retiros Disponibles para 2026!',
                'tipo_contenido' => 'noticia',
                'fecha_creacion' => now()->subDays(1),
                'cuerpo'         => "Estamos emocionados de anunciar el calendario completo de retiros para el primer semestre de 2026. Este año hemos escuchado a nuestra comunidad y preparamos experiencias aún más profundas, accesibles y transformadoras.

NOVEDADES 2026

Retiro Familiar de Bienestar (¡Nuevo!)
Por primera vez ofrecemos un retiro diseñado para familias con hijos de 6 a 12 años. Actividades adaptadas para adultos y niños, con momentos de práctica compartida y espacios independientes. Marzo 2026 en la Sierra.

Retiro Ejecutivo de Mindfulness (¡Nuevo!)
Diseñado específicamente para profesionales y líderes empresariales. 3 días intensivos de meditación, gestión del estrés y liderazgo consciente. Con certificación empresarial. Abril 2026.

Retiro de Yoga y Fotografía (¡Nuevo!)
Una fusión única entre práctica espiritual y expresión artística. Para quienes aman la naturaleza, el yoga y la fotografía. Mayo 2026 en la Amazonía ecuatoriana.

INSCRIPCIONES ANTICIPADAS
Las personas inscritas hasta el 15 de marzo tienen un 15% de descuento en todos los retiros. ¡Los cupos son limitados!

Para reservar tu lugar, contáctanos o inscríbete directamente desde nuestra plataforma.",
            ],
            [
                'titulo'         => 'Guía Completa de Pranayama: Las 5 Técnicas Esenciales de Respiración',
                'tipo_contenido' => 'articulo',
                'fecha_creacion' => now()->subDays(12),
                'cuerpo'         => "El pranayama, el arte del control de la respiración en la tradición yóguica, es posiblemente la herramienta de bienestar más poderosa y accesible que existe. Solo necesitas tu propio cuerpo.

¿QUÉ ES EL PRANAYAMA?
Prana significa energía vital o fuerza de vida. Yama significa control o expansión. Pranayama es, literalmente, la expansión de la energía vital a través de la respiración. En el yoga clásico, es el cuarto de los ocho miembros del camino yóguico.

LAS 5 TÉCNICAS ESENCIALES

1. NADI SHODHANA (Respiración Alterna)
Tapa el orificio derecho con el pulgar. Inhala por el izquierdo 4 tiempos. Tapa ambos, retén 16 tiempos. Exhala por el derecho 8 tiempos. Repite al lado contrario. Equilibra hemisferios cerebrales y calma el sistema nervioso.

2. KAPALABHATI (Respiración del Cráneo Brillante)
Exhalaciones cortas y fuertes por la nariz, el abdomen se contrae activamente. La inhalación es pasiva. Ritmo: 1 exhalación por segundo. Limpia los pulmones, activa el metabolismo y despeja la mente.

3. UJJAYI (Respiración del Océano)
Contrae ligeramente la garganta al respirar, creando un sonido suave como el mar. Inhala y exhala solo por la nariz. Calienta el cuerpo, mejora la concentración y se usa durante la práctica de asanas.

4. BHRAMARI (Respiración del Abejorro)
Exhala haciendo el sonido 'hmmmm' con la boca cerrada. Las vibracines estimulan el nervio vago, reducen la presión arterial y producen calma inmediata. Ideal para dormir.

5. SHITALI (Respiración Refrescante)
Enrolla la lengua en forma de tubo y inhala por ella. Exhala por la nariz. Refresca el cuerpo, reduce la temperatura interna y calma el enojo. Perfecta en verano o momentos de frustración.",
            ],
            [
                'titulo'         => 'Anuncio: Nuevo Programa de Membresía Wellness',
                'tipo_contenido' => 'anuncio',
                'fecha_creacion' => now()->subDays(3),
                'cuerpo'         => "Tenemos una noticia que llevábamos meses preparando con mucho amor para nuestra comunidad: ¡Lanzamos el Programa de Membresía Wellness!

¿QUÉ ES?
Un plan mensual que te da acceso a beneficios exclusivos, descuentos y prioridad en todos nuestros retiros, talleres y productos.

PLANES DISPONIBLES

Plan Semilla - $29/mes
Acceso a clases online ilimitadas, 10% de descuento en retiros y productos, newsletter exclusiva con contenido premium.

Plan Árbol - $59/mes
Todo lo del plan Semilla + acceso prioritario a inscripciones, 20% de descuento, 1 clase presencial gratuita al mes, comunidad privada en WhatsApp.

Plan Raíces - $99/mes
Todo lo anterior + 1 sesión de coaching mensual, 30% de descuento en todo, invitaciones a eventos VIP exclusivos para miembros.

LANZAMIENTO ESPECIAL
Los primeros 50 miembros en inscribirse recibirán el primer mes GRATIS independientemente del plan elegido.

La inscripción estará disponible desde el 15 de marzo de 2026. ¡Mantente atento a nuestra plataforma y redes sociales!",
            ],
        ];

        foreach ($contenidos as $c) {
            DB::table('contenido')->insert([
                'id_administrador' => $adminId,
                'titulo'           => $c['titulo'],
                'cuerpo'           => $c['cuerpo'],
                'tipo_contenido'   => $c['tipo_contenido'],
                'fecha_creacion'   => $c['fecha_creacion'],
            ]);
        }

        $this->command->info('✅ ContenidoSeeder completado: ' . count($contenidos) . ' publicaciones');
    }
}