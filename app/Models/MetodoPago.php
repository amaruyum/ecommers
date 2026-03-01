<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    protected $table = 'METODO_PAGO';
    protected $primaryKey = 'id_metodo_pago';
    public $timestamps = false;

    protected $fillable = ['nombre_metodo'];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_metodo_pago');
    }
}
