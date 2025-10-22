<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLegalPerson;
use App\Models\Cessions\CessionNaturalPerson;
use App\Models\Cessions\CessionNaturalPersonAddress;

class CessionPersonService {

    public function findCINInCessionNaturalPerson ($cin) {
        $naturalPerson = CessionNaturalPerson::with(['address'])->where('cin', $cin)->first();
        
        return $naturalPerson;
    }

    public function findLegalPersonByTPI ($idTPI) {
        $entities = CessionLegalPerson::where('id_tpi', $idTPI)->get();

        return $entities;
    }

    public function findAllAddressCessionNaturalPerson($idCessionNaturalPerson) {

        $addresses = CessionNaturalPersonAddress::where('id_cession_natural_person', $idCessionNaturalPerson)->get();

        return $addresses;
    }
}

