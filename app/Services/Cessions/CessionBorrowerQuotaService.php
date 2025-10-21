<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionBorrower;
use App\Models\Cessions\CessionBorrowerQuota;
use Illuminate\Support\Facades\Log;

class CessionBorrowerQuotaService
{

    public function saveCessionBorrowerQuota($grantedAmount, $idCessionBorrower)
    {
        $quota = CessionBorrowerQuota::create([
            'granted_amount' => $grantedAmount,
            'id_cession_borrower' => $idCessionBorrower
        ]);

        return $quota;
    }

    public function updateCessionBorrowerQuota($idCessionBorrowerQuota, $idCessionBorrower, $grantedAmount)
    {
        $quota = CessionBorrowerQuota::where([
            'id' => $idCessionBorrowerQuota,
            'id_cession_borrower' => $idCessionBorrower
        ])->first();

        
        $quota->granted_amount = $grantedAmount;
        $quota->save();

        return $quota;
    }
    public function deleteCessionBorrowerQuota($idCessionBorrowerQuota)
    {
        $quota = CessionBorrowerQuota::findOrFail($idCessionBorrowerQuota);
        $quota->delete();

        return $quota;
    }
}