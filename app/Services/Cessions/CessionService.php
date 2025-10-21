<?php

namespace App\Services\Cessions;

use App\Models\Cessions\Cession;
use Carbon\Carbon;
use Log;

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
    public function findAllCession () {

        $cessions = Cession::with(['lenders.party', 'borrowers.party',  'borrowers.quota', 'assignment'])->get();

        return $cessions;
    }

    public function findAllCessionByUser ($idUser) {

        $cessions = Cession::
            with(['lenders.party', 'borrowers.party', 'borrowers.quota', 'assignment.user.profil', 'assignment.user.post', 'assignment.user.tpi', 'justificatifs', 'ordonnance'])
            ->where('id_user', $idUser)
            ->get();

        return $cessions;
    }

    public function findAllCessionByTPI ($idTPI) {

        $cessions = Cession::
            with(['lenders.party', 'borrowers.party', 'borrowers.quota', 'assignment.user.profil', 'assignment.user.post', 'assignment.user.tpi', 'justificatifs', 'ordonnance'])
            ->where('id_tpi', $idTPI)
            ->orderByDesc('date_cession')
            ->get();

        return $cessions;
    }

    public function filterCessionByTPI ($idTPI, $statut, $dateStart, $dateEnd) {

        $query = Cession::with(['user.profil', 'tpi', 'lenders.party', 'borrowers.party',  'borrowers.quota', 'assignment']);


        if (!empty($statut) && $statut != 0) {
            $query->where('status_cession', $statut);
        }

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('date_cession', '>', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('date_cession', '<', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }

        $query
            ->where('id_tpi', $idTPI)
            ->orderByDesc('date_cession');
        
        return $query->get();
    }

    public function updateCessionStatus ($idCession, $status) {
        $cession = Cession::findOrFail($idCession);
        $cession->status_cession = $status;
        $cession->save();
    }
}