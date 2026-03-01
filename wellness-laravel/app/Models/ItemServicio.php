<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemServicio extends Model
{
    protected $table      = 'item_servicio';
    protected $primaryKey = 'id_item';
    public    $timestamps = false;
    public    $incrementing = false;

    protected $fillable = [
        'id_item',
        'id_instructor',
        'id_sede',
        'tipo_servicio',
        'fecha_inicio',
        'fecha_fin',
        'itinerario',
        'lugar',
        'cupos_totales',
        'cupos_disponibles',
        'politicas_cancelacion',
    ];

    protected $casts = [
        'fecha_inicio'      => 'datetime',
        'fecha_fin'         => 'datetime',
        'cupos_totales'     => 'integer',
        'cupos_disponibles' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}