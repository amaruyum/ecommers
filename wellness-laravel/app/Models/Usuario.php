<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table      = 'usuario';
    protected $primaryKey = 'id_usuario';
    public    $timestamps  = false;

    protected $fillable = [
        'correo_electronico',
        'contrasena',
        'nombre_completo',
        'telefono',
        'estado_cuenta',
    ];

    protected $hidden = ['contrasena'];

    protected string $authPasswordName = 'contrasena';

    public function cliente(): HasOne
    {
        return $this->hasOne(UsuarioCliente::class, 'id_usuario', 'id_usuario');
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(UsuarioInstructor::class, 'id_usuario', 'id_usuario');
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(UsuarioAdministrador::class, 'id_usuario', 'id_usuario');
    }
}