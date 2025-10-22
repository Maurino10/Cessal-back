<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionReference extends Model
{
    use HasFactory;

    protected $table = 'cession_reference';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'numero_recu',
        'numero_feuillet',
        'numero_repertoire',
        'date',
        'id_cession_borrower',
    ];

    public function cessionBorrower()
    {
        return $this->belongsTo(CessionBorrower::class, 'id_cession_borrower');
    }
}
