<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioCliente extends Model
{
    protected $table      = 'usuario_cliente';
    protected $primaryKey = 'id_usuario';
    public    $timestamps  = false;
    public    $incrementing = false;

    protected $fillable = ['id_usuario', 'ciudad', 'direccion', 'preferencias'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}