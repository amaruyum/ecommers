<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cupon extends Model
{
    protected $table = 'CUPON';
    protected $primaryKey = 'id_cupon';
    public $timestamps = false;

    protected $fillable = ['codigo', 'descripcion', 'valor_descuento', 'fecha_expiracion'];

    protected $casts = [
        'valor_descuento' => 'decimal:2',
        'fecha_expiracion' => 'date',
    ];

    public function puedeTenerCupones(): HasMany
    {
        return $this->hasMany(PuedeTenerCupon::class, 'id_cupon');
    }
}
