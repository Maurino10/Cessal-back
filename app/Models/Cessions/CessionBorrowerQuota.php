<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionBorrowerQuota extends Model
{
    use HasFactory;

    protected $table = 'cession_borrower_quota';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'granted_amount',
        'id_cession_borrower',
    ];

    public function cessionBorrower () {
        return $this->belongsTo(CessionBorrower::class, 'id_cession_borrower');
    }

    protected function percentage(): Attribute
    {
        return Attribute::get(function () {
            $borrower = $this->cessionBorrower;
            if (!$borrower || $borrower->salary_amount == 0) {
                return null; // ou 0 si tu préfères
            }

            return round(($this->granted_amount / $borrower->salary_amount) * 100, 2);
        });
    }

    protected $appends = ['percentage'];
}
