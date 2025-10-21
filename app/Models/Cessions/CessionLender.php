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
        'id_cession_party',
        'id_cession_entity',
        'type'
    ];

    public function cession() {
        return $this->belongsTo(Cession::class, 'id_cession');
    }

    public function party() {
        return $this->belongsTo(CessionParty::class, 'id_cession_party');
    }

    public function entity() {
        return $this->belongsTo(CessionEntity::class, 'id_cession_entity');
    }
}
