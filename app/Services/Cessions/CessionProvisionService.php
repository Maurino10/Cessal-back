<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionProvision;


class CessionProvisionService {

    public function saveProvision($provisionAmount) {
        $provision = CessionProvision::create([
            'provision_amount' => $provisionAmount
        ]);

        return $provision;
    }

    public function updateProvision($idCessionProvision, $provisionAmount) {
        $provision = CessionProvision::findOrFail($idCessionProvision);
        $provision->provision_amount = $provisionAmount;
        $provision->save();

        return $provision;
    }

    public function findProvision() {
        $provision = CessionProvision::all();
        
        return $provision->first();
    }
}