<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table      = 'usuario';       // ← minúsculas para PostgreSQL
    protected $primaryKey = 'id_usuario';
    public $timestamps    = false;

    protected $fillable = [
        'correo_electronico',
        'contrasena',
        'nombre_completo',
        'telefono',
        'estado_cuenta',
    ];

    protected $hidden = ['contrasena'];

    protected $casts = [
        'fecha_registro'     => 'datetime',
        'fecha_modificacion' => 'datetime',
        'contrasena'         => 'hashed',   // ← bcrypt automático en Laravel 10+
    ];

    // ─── Relaciones ───────────────────────────────────────────────
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol');
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

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }
}