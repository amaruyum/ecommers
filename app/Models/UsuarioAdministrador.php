<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsuarioAdministrador extends Model
{
    protected $table = 'USUARIO_ADMINISTRADOR';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_usuario'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function contenidos(): HasMany
    {
        return $this->hasMany(Contenido::class, 'id_administrador');
    }
}
