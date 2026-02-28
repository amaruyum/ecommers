<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuedeTenerCupon extends Model
{
    protected $table = 'PUEDE_TENER_CUPON';
    protected $primaryKey = 'id_puede_tener';
    public $timestamps = false;

    protected $fillable = ['id_cupon', 'id_pedido'];

    public function cupon(): BelongsTo
    {
        return $this->belongsTo(Cupon::class, 'id_cupon');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
