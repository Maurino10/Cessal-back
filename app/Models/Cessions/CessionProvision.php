<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionProvision extends Model
{
    use HasFactory;
    protected $table = 'cession_provision';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'provision_amount',
    ];
}
