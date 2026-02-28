<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'INVENTARIO';
    protected $primaryKey = 'id_inventario';
    public $timestamps = false;

    protected $fillable = ['id_item', 'cantidad', 'tipo_movimiento', 'fecha_movimiento', 'descripcion'];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
