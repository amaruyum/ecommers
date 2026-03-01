<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioInstructor extends Model
{
    protected $table      = 'usuario_instructor';
    protected $primaryKey = 'id_instructor';
    public    $timestamps  = false;

    protected $fillable = ['id_usuario', 'descripcion_perfil', 'especialidad'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}