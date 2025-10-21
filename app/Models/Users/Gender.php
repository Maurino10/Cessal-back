<?php

namespace App\Models\Users;

use App\Models\Cessions\CessionParty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'gender';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name'
    ];

    public function inscription() 
    {
        return $this->hasMany(Inscription::class, 'id_gender');
    }

    public function profil() 
    {
        return $this->hasMany(Profil::class, 'id_gender');
    }

    public function cessionParty() 
    {
        return $this->hasMany(CessionParty::class, 'id_gender');
    }
}
