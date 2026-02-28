<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoDetalle extends Model
{
    protected $table = 'PEDIDO_DETALLE';
    protected $primaryKey = 'id_pedido_detalle';
    public $timestamps = false;

    protected $fillable = ['id_pedido', 'id_item', 'cantidad', 'precio', 'subtotal'];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
