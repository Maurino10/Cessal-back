<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionJustificatif extends Model
{
    protected $table = 'cession_justificatif';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'integer';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
        'id_cession'
    ];


    public function cession() {
        return $this->belongsTo(Cession::class, 'id_cession');
    }
}
