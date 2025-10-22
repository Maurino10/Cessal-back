<?php

namespace App\Models\Instances;

use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionLegalPerson;
use App\Models\Territories\District;
use App\Models\Territories\Province;
use App\Models\Territories\Region;
use App\Models\Users\Inscription;
use App\Models\Users\Post;
use App\Models\Users\User;
use App\Models\Users\UserInscription;
use App\Models\Users\UserPost;
use Illuminate\Database\Eloquent\Model;

class Tpi extends Model
{
    protected $table = 'tpi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'name',
        'id_ca',
        'id_district'
    ];


    public function ca()
    {
        return $this->belongsTo(Ca::class, 'id_ca');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_tpi');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_tpi');
    }

    public function cessions() {
        return $this->hasMany(Cession::class,'id_tpi');
    }

    public function entities() {
        return $this->hasMany(CessionLegalPerson::class,'id_tpi');
    }
}
