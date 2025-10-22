<?php

namespace App\Models\Cessions;

use App\Models\Users\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionNaturalPerson extends Model
{
    use HasFactory;

    protected $table = 'cession_natural_person';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'last_name',
        'first_name',
        'cin',
        'id_gender'
    ];

    
    public function gender () {
        return $this->belongsTo(Gender::class,'id_gender');
    }
    
    public function address() {
        return $this->hasMany(CessionNaturalPersonAddress::class, 'id_cession_natural_person');
    }

    public function cessionLender () {
        return $this->hasMany(CessionLender::class, 'id_cession_natural_person');
    }

    public function cessionBorrower () {
        return $this->hasMany(CessionBorrower::class, 'id_cession_natural_person');
    }
}
