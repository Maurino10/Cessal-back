<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLegalPerson;
use App\Models\Cessions\CessionNaturalPerson;
use App\Models\Cessions\CessionNaturalPersonAddress;
use Carbon\Carbon;

class CessionNaturalPersonService {

    public function saveCessionNaturalPerson($lastName, $firstName, $cin, $idGender) {

        $naturalPerson = CessionNaturalPerson::insertGetId([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'cin' => $cin,
            'id_gender' => $idGender
        ]);
        
        return $naturalPerson;
    }

    public function saveCessionNaturalPersonAddress($address, $idCessionNaturalPerson) {

        $address = CessionNaturalPersonAddress::insertGetId([
            'address' => $address,
            'id_cession_natural_person' => $idCessionNaturalPerson
        ]);

        return $address;
    }

    public function updateCessionNaturalPerson($idCessionNaturalPerson, $lastName, $firstName, $cin, $idGender) {

        $naturalPerson = CessionNaturalPerson::findOrFail($idCessionNaturalPerson);

        $naturalPerson->last_name = $lastName;
        $naturalPerson->first_name = $firstName;
        $naturalPerson->cin = $cin;
        $naturalPerson->id_gender = $idGender;
        $naturalPerson->save();
        
        return $naturalPerson;
    }

}

