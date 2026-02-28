<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsuarioCliente extends Model
{
    protected $table = 'USUARIO_CLIENTE';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'ciudad', 'direccion', 'preferencias'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'id_cliente');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_cliente');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_cliente');
    }

    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class, 'id_cliente');
    }
}
