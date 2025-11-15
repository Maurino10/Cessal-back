<?php

namespace App\Models\Cessions;

use App\Models\Instances\Tpi;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cession extends Model
{
    use HasFactory;
    protected $table = 'cession';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = [
        'numero_dossier',
        'date_contrat',
        'request_subject',
        'reimbursed_amount',
        'date_cession',
        'status_cession',
        'signed',
        'id_tpi',
        'id_user',
    ];


    public function tpi() {
        return $this->belongsTo(Tpi::class, 'id_tpi');
    }

    public function user() {
        return $this->belongsTo(User::class,'id_user');
    }

    public function assignment() {
        return $this->hasOne(CessionMagistrat::class,'id_cession');
    }

    public function lenders() {
        return $this->hasMany(CessionLender::class,'id_cession');
    }

    public function borrowers() {
        return $this->hasMany(CessionBorrower::class,'id_cession');
    }

    public function justificatifs() {
        return $this->hasMany(CessionJustificatif::class,'id_cession');
    }

    public function ordonnance()
    {
        return $this->hasOne(CessionOrdonnance::class, 'id_cession');
    }
  
    // Mapping des statuts
    public const STATUSES = [
        0 => 'Enregistrée',
        1 => 'En cours d\'approbation',
        2 => 'Approuvée',
        3 => 'Rejetée',
        4 => 'Signée',
    ];

    public const STATUS_COLORS = [
        0 => '#2196F3', // gris
        1 => '#FB8C00', // bleu
        2 => '#4CAF50', // vert
        3 => '#F44336', // rouge
        4 => '#8E24AA', // turquoise
    ];

    // Accessor pour obtenir le libellé/
    public function getStatusLabelAttribute()
    {
        if ($this->signed === 1) {
            return self::STATUSES[4] ?? 'Inconnu';
        }
        return self::STATUSES[$this->status_cession] ?? 'Inconnu';
    }

    public function getStatusColorAttribute()
    {
        if ($this->signed === 1) {
            return self::STATUS_COLORS[4] ?? '#000000';
        }
        return self::STATUS_COLORS[$this->status_cession] ?? '#000000'; // noir par défaut
    }

    public function getCanAcceptAttribute()
    {
        // 1. Vérifier l'ordonnance
        if (!$this->ordonnance || empty($this->ordonnance->numero_ordonnance)) {
            return false;
        }
        // 2. Vérifier les borrowers et leurs quotas
        foreach ($this->borrowers as $borrower) {
            // si pas de quotas OU si granted_amount est null ou 0 => refus
            if (empty($borrower->quota)) {
                return false;
            }
            
            if (empty($borrower->quota->granted_amount) || $borrower->quota->granted_amount <= 0) {
                return false;
            }

        }

        return true;
    }

    protected $appends = ['status_label', 'status_color'];

}       
