<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'PEDIDO';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    protected $fillable = ['id_cliente', 'fecha', 'estado', 'total_general'];

    protected $casts = [
        'fecha' => 'datetime',
        'total_general' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(UsuarioCliente::class, 'id_cliente');
    }

    public function pedidoDetalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'id_pedido');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_pedido');
    }

    public function puedeTenerCupones(): HasMany
    {
        return $this->hasMany(PuedeTenerCupon::class, 'id_pedido');
    }
}
