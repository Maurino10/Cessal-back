<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionProvision;
use App\Models\Cessions\CessionReference;


class CessionReferenceService {

    public function saveReference($numeroRecu, $numeroFeuillet, $numeroRepertoire, $date, $idCessionBorrower) {
        $reference = CessionReference::create([
            'numero_recu' => $numeroRecu,
            'numero_feuillet' => $numeroFeuillet,
            'numero_repertoire' => $numeroRepertoire,
            'date' => $date,
            'provision' => CessionProvision::all()->first()->provision_amount,
            'id_cession_borrower' => $idCessionBorrower,
        ]);

        return $reference;
    }

    public function updateReference ($idCessionReference, $numeroRecu, $numeroFeuillet, $numeroRepertoire, $date) {
        $reference = CessionReference::findOrFail($idCessionReference);
        $reference->numero_recu = $numeroRecu;
        $reference->numero_feuillet = $numeroFeuillet;
        $reference->numero_repertoire = $numeroRepertoire;
        $reference->date = $date;
        $reference->save();

        return $reference;
    }

    public function findReference ($idCessionReference) {
        $reference = CessionReference::findOrFail($idCessionReference);

        return $reference;
    }


}