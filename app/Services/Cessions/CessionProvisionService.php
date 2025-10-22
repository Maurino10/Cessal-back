<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionProvision;
use Carbon\Carbon;


class CessionProvisionService {

    public function saveProvision($provisionAmount, $dateProvision) {
        $provision = CessionProvision::create([
            'provision_amount' => $provisionAmount,
            'date_provision' => $dateProvision
        ]);

        return $provision;
    }

    public function updateProvision($idCessionProvision, $provisionAmount, $dateProvision) {
        $provision = CessionProvision::findOrFail($idCessionProvision);
        $provision->provision_amount = $provisionAmount;
        $provision->date_provision = $dateProvision;
        $provision->save();

        return $provision;
    }

    public function findAllProvision() {
        $provisions = CessionProvision::orderBy('date_provision', 'desc')->get();
        
        return $provisions;
    }

    public function findProvisionDateCession($dateCession) {
        $provision = CessionProvision::where('date_provision', '<=', $dateCession)
                        ->orderBy('date_provision', 'desc')
                        ->first();

        
        return $provision;
    }
}