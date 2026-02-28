<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemProducto extends Model
{
    protected $table = 'ITEM_PRODUCTO';
    protected $primaryKey = 'id_item';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_item', 'marca', 'fecha_elaboracion', 'fecha_caducidad', 'stock_disponible'];

    protected $casts = [
        'fecha_elaboracion' => 'date',
        'fecha_caducidad' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
