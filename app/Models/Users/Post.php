<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'name', 
        'role'
    ];


    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_post');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_post');
    }

    public const POST_COLORS = [
        'magistrat'=> '#A855F7',
        'greffier'=> '#06B6D4',
        'admin_local'=> '#84cc16'
    ];

    public function getPostColorAttribute()
    {
        return self::POST_COLORS[$this->role] ?? '#000000'; // noir par défaut
    }

    protected $appends = ['post_color'];
}
