<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionNaturalPersonAddress extends Model
{
    use HasFactory;

    protected $table = 'cession_natural_person_address';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'address',
        'id_cession_natural_person',
    ];

    public function naturalPerson() {
        return $this->belongsTo(CessionNaturalPerson::class, 'id_cession_natural_person');
    }

    public function cessionLender() {
        return $this->hasOne(CessionLender::class, 'id_cession_natural_person');
    }

    public function cessionBorrower() {
        return $this->hasOne(CessionLender::class, 'id_cession_natural_person');
    }
}
