<?php

namespace App\Services\Cessions;

use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function findAllCessionByMagistrat($idUser, $search, $statut) {

        $cessions = CessionMagistrat::query()
            ->join('cession', 'cession.id', '=', 'cession_magistrat.id_cession')
            ->where('cession_magistrat.id_user', $idUser)
            ->select('cession_magistrat.*') // éviter les collisions de colonnes
            ->orderByDesc('cession.date_cession'); // tri SQL direct

        if (!empty($search)) {
            $cessions->where(function ($query) use ($search) {
                $query->where('cession.numero_dossier', 'ILIKE', "%$search%")
                    ->orWhereHas('cession.lenders.naturalPerson', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'ILIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'ILIKE', "%$search%");
                    })
                    ->orWhereHas('cession.lenders.legalPerson', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%$search%");
                    })
                    ->orWhereHas('cession.borrowers.naturalPerson', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'ILIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'ILIKE', "%$search%");
                    });
            });
        }

        if ($statut != 'null' && $statut != -1 && $statut != 4) {
            $cessions->where('cession.status_cession', $statut);
            $cessions->where('cession.signed', 0);
        }

        if ($statut == 4) {
            $cessions->where('cession.signed', 1);
        }

        return $cessions->with([
            'user.profil',
            'user.post',
            'user.tpi',
            'cession.ordonnance'
        ])
        ->orderByDesc('cession.date_cession')
        ->paginate(perPage: 10);
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