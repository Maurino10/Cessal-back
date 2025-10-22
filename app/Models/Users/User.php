<?php

namespace App\Models\Users;

use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Models\Instances\Tpi;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'password',
        'id_profil',
        'id_post',
        'id_tpi',
    ];

    protected $hidden = [
        'password'
    ];

    public function profil() 
    {
        return $this->belongsTo(Profil::class, 'id_profil');
    }

    public function post() 
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function tpi() 
    {
        return $this->belongsTo(Tpi::class, 'id_tpi');
    }

    public function cessions() {
        return $this->hasMany(Cession::class,'id_user');
    }

    public function cessionMagistrat() {
        return $this->hasMany(CessionMagistrat::class,'id_user');
    } 
    public function hasRole($roleName)
    {
        return $this->post()->where('role', $roleName)->exists();
    }

    public function isGreffier () {
        return $this->post->role === 'greffier';
    }

    public function isMagistrat () {
        return $this->post->role === 'magistrat';
    }

    public function isAdminLocal () {
        return $this->post->role === 'admin_local';
    }

    public function isMinistere () {
        return $this->post->role === 'ministere';
    }
}
