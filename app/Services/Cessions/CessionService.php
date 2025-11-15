<?php

namespace App\Services\Cessions;

use App\Models\Cessions\Cession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CessionService {

    public function saveCession($numeroDossier, $dateContrat, $requestSubject, $reimbursedAmount, $dateCession, $idTPI, $idUser) {

        $cession = Cession::insertGetId([
            'numero_dossier' => $numeroDossier,
            'date_contrat' => $dateContrat,
            'request_subject' => $requestSubject,
            'reimbursed_amount' => $reimbursedAmount,
            'date_cession' => !empty($dateCession) ? $dateCession : Carbon::now(),
            'id_tpi' => $idTPI,
            'id_user' => $idUser,        
        ]);

        return $cession;
    }

    public function updateCession($idCession, $numeroDossier, $dateContrat, $requestSubject, $reimbursedAmount, $dateCession) {

        $cession = Cession::findOrFail($idCession);
        $cession->numero_dossier = $numeroDossier;
        $cession->date_contrat = $dateContrat;
        $cession->request_subject = $requestSubject;
        $cession->reimbursed_amount = $reimbursedAmount;
        $cession->date_cession = $dateCession;
        $cession->save();

        return $cession;
    }

    public function updateCessionStatus ($idCession, $status) {
        $cession = Cession::findOrFail($idCession);
        $cession->status_cession = $status;
        $cession->save();
    }

    public function cessionIsSigned ($idCession, $isSigned) {
        $cession = Cession::findOrFail($idCession);
        $cession->signed = $isSigned;
        $cession->save();
    }

    public function findCession ($idCession) {

        $cession = Cession::with(['ordonnance'])->findOrFail($idCession);

        return $cession;
    }

    public function findCessionWithAttributCanAccept ($idCession) {

        $cession = Cession::with(['ordonnance'])->findOrFail($idCession);
        $cession->append('can_accept');

        return $cession;
    }

    public function findCessionWithHisMagitrat($idCession) {

        $cession = Cession::with(['ordonnance', 'assignment'])->findOrFail($idCession);

        return $cession;
    }
    
    public function findAllCession ($idTPI, $statut, $dateStart, $dateEnd) {

        $cessions = Cession::with([
            'assignment.user.profil', 
            'assignment.user.post', 
            'assignment.user.tpi', 
            'ordonnance', 
            'tpi'
        ]);

        if (!empty($idTPI) && $idTPI !== 'null' && $idTPI !== '') {
            $cessions->where('id_tpi', $idTPI);
        }

        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $cessions->where('status_cession', $statut);
            $cessions->where('signed', 0);
        }

        if ($statut == 4) {
            $cessions->where('signed', 1);
        }

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $cessions->where('date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $cessions->where('date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $cessions->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }

        return $cessions
            ->orderByDesc('date_cession')
            ->orderBy('numero_dossier')
            ->paginate(10);
    }

    public function findAllCessionByGreffier ($idUser, $search, $statut) {

        $cessions = Cession::with([
            'assignment.user.profil', 
            'assignment.user.post', 
            'assignment.user.tpi', 
            'ordonnance'
        ])->where('id_user', $idUser);
        
        if (!empty($search) && $search !== '') {
            $cessions->where(function ($query) use ($search) {
                $query->where('numero_dossier', 'ILIKE', "%$search%")
                    ->orWhereHas('lenders.naturalPerson', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'ILIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'ILIKE', "%$search%");
                    })
                    ->orWhereHas('lenders.legalPerson', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%$search%");
                    })
                    ->orWhereHas('borrowers.naturalPerson', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'ILIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'ILIKE', "%$search%");
                    });     
            });
        }

        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $cessions->where('status_cession', $statut);
            $cessions->where('signed', 0);
        }

        if ($statut == 4) {
            $cessions->where('signed', 1);
        }

        return $cessions
            ->orderByDesc('date_cession')
            ->paginate(10);
    }

    public function findAllCessionByTPI ($idTPI, $statut, $dateStart, $dateEnd) {

        $cessions = Cession::with([
            'assignment.user.profil', 
            'assignment.user.post', 
            'assignment.user.tpi', 
            'ordonnance'
        ])->where('id_tpi', $idTPI);


        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $cessions->where('status_cession', $statut);
            $cessions->where('signed', 0);
        }

        if ($statut == 4) {
            $cessions->where('signed', 1);
        }

        if ($statut == 4) {
            $cessions->where('signed', 1);
        }

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $cessions->where('date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $cessions->where('date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $cessions->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }
    

        return $cessions
            ->orderByDesc('date_cession')
            ->paginate(10);
    }

    public function filterCessionByTPI ($idTPI, $statut, $dateStart, $dateEnd) {

        $query = Cession::with(['user.profil',
            'tpi',
            'lenders.naturalPerson',
            'lenders.legalPerson',
            'borrowers.naturalPerson',
            'borrowers.quota',
            'assignment'
        ]);


        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $query->where('status_cession', $statut);
            $query->where('signed', 0);
        }

        if ($statut == 4) {
            $query->where('signed', 1);
        }

        if ($statut == 4) {
            $query->where('signed', 1);
        }

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }
        
        return $query
            ->where('id_tpi', $idTPI)
            ->orderByDesc('date_cession')
            ->get();
    }

    public function filterCession ($idTPI, $statut, $dateStart, $dateEnd) {

        $query = Cession::with([
            'user.profil',
            'tpi',
            'lenders.naturalPerson',
            'lenders.legalPerson',
            'borrowers.naturalPerson',
            'borrowers.quota', 'assignment'
        ]);

        if (!empty($idTPI) && $idTPI !== 'null' && $idTPI !== '') {
            $query->where('id_tpi', $idTPI);
        }

        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $query->where('status_cession', $statut);
            $query->where('signed', 0);
        }

        if ($statut == 4) {
            $query->where('signed', 1);
        }

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }

        
        return $query
            ->orderByDesc('date_cession')
            ->get();
    }

}