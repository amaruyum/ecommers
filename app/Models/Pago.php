<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'PAGO';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = ['id_pedido', 'id_metodo_pago', 'total', 'fecha_pago', 'estado'];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago');
    }
}
