<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $table = 'CARRITO';
    protected $primaryKey = 'id_carrito';
    public $timestamps = false;

    protected $fillable = ['id_cliente', 'fecha', 'estado'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(UsuarioCliente::class, 'id_cliente');
    }

    public function carritoItems(): HasMany
    {
        return $this->hasMany(CarritoItem::class, 'id_carrito');
    }
}
