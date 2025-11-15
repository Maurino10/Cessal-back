<?php

namespace App\Models\Cessions;

use App\Models\Instances\Tpi;
use App\Models\Users\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionLegalPerson extends Model
{
    use HasFactory;

    protected $table = 'cession_legal_person';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name',
        'id_tpi',
    ];

    public function tpi() {
        return $this->belongsTo(Tpi::class, 'id_tpi');
    }

    public function cessionLender () {
        return $this->hasMany(CessionLender::class, 'id_cession_legal_person');
    }

}
