<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionLegalPersonAddress extends Model
{
    use HasFactory;
    protected $table = 'cession_legal_person_address';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'address',
        'id_cession_legal_person',
    ];

    public function legalPerson() {
        return $this->belongsTo(CessionLegalPerson::class, 'id_cession_legal_person');
    }

    public function cessionLender() {
        return $this->hasOne(CessionLender::class, 'id_cession_legal_person');
    }

}
