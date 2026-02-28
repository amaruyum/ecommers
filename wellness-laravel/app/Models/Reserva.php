<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $table = 'RESERVA';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = ['id_cliente', 'id_servicio', 'fecha_reserva', 'estado'];

    protected $casts = [
        'fecha_reserva' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(UsuarioCliente::class, 'id_cliente');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(ItemServicio::class, 'id_servicio');
    }
}
