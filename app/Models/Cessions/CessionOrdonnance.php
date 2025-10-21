<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionOrdonnance extends Model
{
    use HasFactory;

    protected $table = 'cession_ordonnance';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'numero_ordonnance',
        'id_cession',
    ];

    public function cession()
    {
        return $this->belongsTo(Cession::class, 'id_cession');
    }
}
