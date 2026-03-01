<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioRol extends Model
{
    protected $table = 'USUARIO_ROL';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'id_rol'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
