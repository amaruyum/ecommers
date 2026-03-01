<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogTransaccion extends Model
{
    protected $table = 'LOG_TRANSACCION';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = ['id_pedido', 'id_pago', 'id_usuario'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
