<?php

namespace App\Models\Cessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CessionBorrower extends Model
{
    use HasFactory;

    protected $table = 'cession_borrower';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'salary_amount',
        'remark',
        'id_cession',
        'id_cession_natural_person',
        'id_cession_natural_person_address',
    ];

    public function cession() {
        return $this->belongsTo(Cession::class);
    }

    public function naturalPerson() {
        return $this->belongsTo(CessionNaturalPerson::class, 'id_cession_natural_person');
    }

    public function naturalPersonAddress() {
        return $this->belongsTo(CessionNaturalPersonAddress::class, 'id_cession_natural_person_address');
    }

    public function quota() {
        return $this->hasOne(CessionBorrowerQuota::class,'id_cession_borrower');
    }

    public function reference() {
        return $this->hasOne(CessionReference::class, 'id_cession_borrower');
    } 

    public const STATUSES = [
        0 => 'En attente',
        1 => 'Traité',
    ];

    public const STATUS_COLORS = [
        0 => '#FFC107', // rouge
        1 => '#1ece7cff', // vert
    ];

    public function getStatusLabelAttribute()
    {
        return $this->quota()->exists() ? self::STATUSES[1] : self::STATUSES[0];
    }

    public function getStatusColorAttribute()
    {
        return $this->quota()->exists() ? self::STATUS_COLORS[1] : self::STATUS_COLORS[0];
    }

    protected $appends = ['status_label', 'status_color'];

}
