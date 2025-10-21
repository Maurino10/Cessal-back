<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionPartyAddress extends Model
{
    use HasFactory;

    protected $table = 'cession_party_address';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'address',
        'date_address',
        'id_cession_party',
    ];

    public function party() {
        return $this->belongsTo(CessionParty::class, 'id_cession_party');
    }
}
