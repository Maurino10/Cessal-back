<?php

namespace App\Services\Cessions;

use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionOrdonnance;

class CessionOrdonnanceService
{

    public function saveCessionOrdonnance($numeroOrdonnance, $idCession)
    {
        $ordonnance = CessionOrdonnance::create([
            'numero_ordonnance' => $numeroOrdonnance,
            'id_cession' => $idCession
        ]);

        return $ordonnance;
    }

    public function updateCessionOrdonnance($idCessionOrdonnance, $idCession, $numeroOrdonnance)
    {
        $ordonnance = CessionOrdonnance::where([
            'id' => $idCessionOrdonnance,
            'id_cession' => $idCession
        ])->first();
        $ordonnance->numero_ordonnance = $numeroOrdonnance;
        $ordonnance->save();

        return $ordonnance;
    }

    public function deleteCessionOrdonnance($idCessionOrdonnance)
    {
        $ordonnance = CessionOrdonnance::findOrFail($idCessionOrdonnance);
        $ordonnance->delete();

        return $ordonnance;
    }
}