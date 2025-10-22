<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionLender extends Model
{
    use HasFactory;

    protected $table = 'cession_lender';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'id_cession',
        'id_cession_natural_person',
        'id_cession_legal_person',
        'id_cession_natural_person_address',
        'type'
    ];

    public function cession() {
        return $this->belongsTo(Cession::class, 'id_cession');
    }

    public function naturalPerson() {
        return $this->belongsTo(CessionNaturalPerson::class, 'id_cession_natural_person');
    }

    public function naturalPersonAddress() {
        return $this->belongsTo(CessionNaturalPersonAddress::class, 'id_cession_natural_person_address');
    }

    public function legalPerson() {
        return $this->belongsTo(CessionLegalPerson::class, 'id_cession_legal_person');
    }
}
