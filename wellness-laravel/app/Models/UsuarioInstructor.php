<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsuarioInstructor extends Model
{
    protected $table = 'USUARIO_INSTRUCTOR';
    protected $primaryKey = 'id_instructor';
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'descripcion_perfil', 'especialidad'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function itemServicios(): HasMany
    {
        return $this->hasMany(ItemServicio::class, 'id_instructor');
    }
}
