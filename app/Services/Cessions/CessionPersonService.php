<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLegalPerson;
use App\Models\Cessions\CessionLegalPersonAddress;
use App\Models\Cessions\CessionNaturalPerson;
use App\Models\Cessions\CessionNaturalPersonAddress;

class CessionPersonService {

    public function findCINInCessionNaturalPerson ($cin) {
        $naturalPerson = CessionNaturalPerson::with(['address'])->where('cin', $cin)->first();
        
        return $naturalPerson;
    }

    public function findLegalPersonByTPI ($idTPI) {
        $legalPersons = CessionLegalPerson::where('id_tpi', $idTPI)->get();

        return $legalPersons;
    }
    
    public function findAllAddressCessionNaturalPerson($idCessionNaturalPerson) {

        $addresses = CessionNaturalPersonAddress::where('id_cession_natural_person', $idCessionNaturalPerson)->get();

        return $addresses;
    }

    public function findAllAddressCessionLegalPerson($idCessionLegalPerson) {
        
        $addresses = CessionLegalPersonAddress::where('id_cession_legal_person', $idCessionLegalPerson)->get();

        return $addresses;
    }
}

