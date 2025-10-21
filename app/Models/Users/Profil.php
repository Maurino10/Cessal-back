<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'profil';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'last_name',
        'first_name',
        'birthday',
        'address',
        'cin',
        'immatriculation',
        'email',
        'id_gender',
    ];

    public function gender() 
    {
        return $this->belongsTo(Gender::class,'id_gender');
    }

    public function user()
    {   
        return $this->hasOne(User::class,'id_profil');
    }


}
