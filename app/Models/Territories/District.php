<?php

namespace App\Models\Territories;

use App\Models\Instances\Tpi;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'district';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name',
        'id_region',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    public function tpis()
    {
        return $this->hasMany(Tpi::class, 'id_district');
    }
}
