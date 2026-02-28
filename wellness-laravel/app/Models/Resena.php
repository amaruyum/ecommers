<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resena extends Model
{
    protected $table = 'RESENA';
    protected $primaryKey = 'id_resena';
    public $timestamps = false;

    protected $fillable = ['id_cliente', 'id_item', 'puntuacion', 'comentario', 'fecha'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(UsuarioCliente::class, 'id_cliente');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
