<?php

namespace App\Models\Territories;

use App\Models\Instances\Ca;
use App\Models\Instances\Tpi;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name',
    ];


    public function cas()
    {
        return $this->hasMany(Ca::class, 'id_province');
    }
    public function regions()
    {
        return $this->hasMany(Region::class, 'id_province');
    }

    public function tpis()
    {
        return $this->hasMany(Tpi::class, 'id_province');
    }
}
