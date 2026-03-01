<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemServicio extends Model
{
    protected $table = 'ITEM_SERVICIO';
    protected $primaryKey = 'id_item';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_item', 'id_instructor', 'id_sede', 'tipo_servicio', 'fecha_inicio', 'fecha_fin',
        'itinerario', 'lugar', 'cupos_totales', 'cupos_disponibles', 'politicas_cancelacion'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(UsuarioInstructor::class, 'id_instructor');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_servicio');
    }
}
