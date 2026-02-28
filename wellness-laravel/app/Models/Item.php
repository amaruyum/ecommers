<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $table = 'ITEM';
    protected $primaryKey = 'id_item';
    public $timestamps = false;

    protected $fillable = ['id_categoria', 'nombre', 'descripcion', 'estado', 'precio'];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function itemProducto(): HasOne
    {
        return $this->hasOne(ItemProducto::class, 'id_item');
    }

    public function itemServicio(): HasOne
    {
        return $this->hasOne(ItemServicio::class, 'id_item');
    }

    public function inventarios(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inventario::class, 'id_item');
    }

    public function resenas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resena::class, 'id_item');
    }
}
