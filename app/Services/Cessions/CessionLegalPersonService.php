<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLegalPerson;
class CessionLegalPersonService {

    public function saveCessionLegalPerson($name, $address, $idTPI) {
        $legalPerson = CessionLegalPerson::insertGetId([
            'name' => $name,
            'address' => $address,
            'id_tpi' => $idTPI
        ]);

        return $legalPerson;
    }


    public function updateCessionLegalPerson($idCessionLegalPerson, $name, $address) {

        $legalPerson = CessionLegalPerson::findOrFail($idCessionLegalPerson);

        $legalPerson->name = $name;
        $legalPerson->address = $address;
        $legalPerson->save();
        
        return $legalPerson;
    }

}

