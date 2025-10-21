<?php

namespace App\Models\Instances;

use App\Models\Territories\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ca extends Model
{
    protected $table = 'ca';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name',
        'id_province',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province');
    }

    public function tpis()
    {
        return $this->hasMany(Tpi::class, 'id_ca');
    }
}
