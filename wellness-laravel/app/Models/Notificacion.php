<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'NOTIFICACION';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'tipo', 'contenido_mensaje', 'fecha_envio', 'estado'];

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
