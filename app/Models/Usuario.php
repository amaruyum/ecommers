<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasApiTokens;

    protected $table = 'USUARIO';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'correo_electronico', 'contrasena', 'nombre_completo', 'telefono', 'estado_cuenta'
    ];

    protected $hidden = ['contrasena'];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'fecha_modificacion' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'USUARIO_ROL', 'id_usuario', 'id_rol');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(UsuarioCliente::class, 'id_usuario');
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(UsuarioInstructor::class, 'id_usuario');
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(UsuarioAdministrador::class, 'id_usuario');
    }

    public function notificaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }
}
