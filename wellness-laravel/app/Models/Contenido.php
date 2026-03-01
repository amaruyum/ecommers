<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contenido extends Model
{
    protected $table      = 'contenido';
    protected $primaryKey = 'id_contenido';
    public    $timestamps  = false;

    protected $fillable = [
        'id_administrador',
        'titulo',
        'cuerpo',
        'fecha_creacion',
        'tipo_contenido',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(UsuarioAdministrador::class, 'id_administrador', 'id_administrador');
    }
}