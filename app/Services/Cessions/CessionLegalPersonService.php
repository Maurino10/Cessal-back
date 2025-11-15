<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLegalPerson;
use App\Models\Cessions\CessionLegalPersonAddress;
class CessionLegalPersonService {

    public function saveCessionLegalPerson($name, $idTPI) {
        $legalPerson = CessionLegalPerson::insertGetId([
            'name' => $name,
            'id_tpi' => $idTPI
        ]);

        return $legalPerson;
    }


    public function saveCessionLegalPersonAddress($address, $idCessionLegalPerson) {

        $address = CessionLegalPersonAddress::insertGetId([
            'address' => $address,
            'id_cession_legal_person' => $idCessionLegalPerson
        ]);

        return $address;
    }

}

