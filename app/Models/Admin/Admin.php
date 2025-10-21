<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'admin';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'login',
        'password'
    ];

    protected $hidden = [
        'password'
    ];
}
