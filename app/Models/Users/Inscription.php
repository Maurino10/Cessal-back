<?php

namespace App\Models\Users;

use App\Models\Instances\Tpi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $table = 'inscription';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'integer';
    public $timestamps = false;


    protected $fillable = [
        'last_name',
        'first_name',
        'birthday',
        'address',
        'cin',
        'immatriculation',
        'email',
        'password',
        'date_inscription',
        'date_acceptation',
        'status',
        'id_gender',
        'id_tpi',
        'id_post'
    ];

    public function gender() 
    {
        return $this->belongsTo(Gender::class,'id_gender');
    }

    public function tpi() 
    {
        return $this->belongsTo(Tpi::class, 'id_tpi');
    }
    public function post() 
    {
        return $this->belongsTo(Post::class,'id_post');
    }
}
