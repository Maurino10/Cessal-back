<?php

namespace App\Models\Territories;

use App\Models\Instances\Tpi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';
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

    public function districts()
    {
        return $this->hasMany(District::class,'id_region');
    }

}
