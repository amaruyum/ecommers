<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $table      = 'item';
    protected $primaryKey = 'id_item';
    public    $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
        'precio',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function producto(): HasOne
    {
        return $this->hasOne(ItemProducto::class, 'id_item', 'id_item');
    }

    public function servicio(): HasOne
    {
        return $this->hasOne(ItemServicio::class, 'id_item', 'id_item');
    }
}