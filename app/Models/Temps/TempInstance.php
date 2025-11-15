<?php

namespace App\Models\Temps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempInstance extends Model
{

    protected $table = 'temp_instance';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'integer';
    public $timestamps = false;


    protected $fillable = [
        'structure_parente',
        'structure_fille',
        'province',
        'region',
        'district',
    ];
}
