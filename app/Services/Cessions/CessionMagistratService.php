<?php

namespace App\Services\Cessions;

use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Models\Users\User;

class CessionMagistratService {

    public function saveCessionMagistrat($idUser, $idCession) {

        $magistrat = CessionMagistrat::create([
            'id_user' => $idUser,
            'id_cession' => $idCession
        ]);

        $cession = Cession::findOrFail($idCession);
        $cession->status_cession = 1;
        $cession->save();

        return $magistrat;
    }

    public function updateCessionMagistrat($idCessionMagistrat, $idUser) {
        $magistrat = CessionMagistrat::findOrFail($idCessionMagistrat);
        $magistrat->id_user = $idUser;
        $magistrat->save();

        return $magistrat;
    }

    public function findMagistratByCession($idCession) {
        $cession = CessionMagistrat::with(['user.profil', 'user.post', 'user.tpi'])
                        ->where('id_cession', $idCession)
                        ->first();

        return $cession; 
    }

    public function findAllCessionByMagistrat($idUser) {

        $cessions = CessionMagistrat::
            with(['cession.lenders.party', 'cession.borrowers.party', 'cession.borrowers.quota', 'cession.justificatifs', 'user.profil', 'user.post', 'user.tpi', 'cession.ordonnance'])
            ->where('id_user', $idUser)
            ->get();

        return $cessions;
    }

    public function findAllMagistratByTpi($idTPI) {
        $magistrats = User::with(['profil', 'tpi', 'post'])
            ->where('id_tpi', $idTPI)
            ->whereHas('post', function ($query) {
                $query->where('role', 'magistrat');
            })
            ->get();

        return $magistrats;
    }
}