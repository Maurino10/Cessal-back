<?php

namespace App\Models\Cessions;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionMagistrat extends Model
{
    use HasFactory;

    protected $table = 'cession_magistrat';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'id_cession',
        'id_user',
    ];

    public function cession() {
        return $this->belongsTo(Cession::class, 'id_cession');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
