<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    protected $table = 'SEDE';
    protected $primaryKey = 'id_sede';
    public $timestamps = false;

    protected $fillable = ['nombre', 'ciudad', 'direccion'];

    public function itemServicios(): HasMany
    {
        return $this->hasMany(ItemServicio::class, 'id_sede');
    }
}
