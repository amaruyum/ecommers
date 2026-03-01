<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoItem extends Model
{
    protected $table = 'CARRITO_ITEM';
    protected $primaryKey = 'id_carrito_item';
    public $timestamps = false;

    protected $fillable = ['id_carrito', 'id_item', 'cantidad', 'precio', 'subtotal'];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'id_carrito');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
