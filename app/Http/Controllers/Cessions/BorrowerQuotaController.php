<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionBorrowerQuotaRequest;
use App\Models\Cessions\CessionMagistrat;
use App\Services\Cessions\CessionBorrowerQuotaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BorrowerQuotaController extends Controller
{
    protected $cessionBorrowerQuotaService;

    public function __construct(CessionBorrowerQuotaService $cessionBorrowerQuotaService)
    {

        $this->cessionBorrowerQuotaService = $cessionBorrowerQuotaService;
    }


    public function storeCessionBorrowerQuota($idCession, $idCessionBorrower, CessionBorrowerQuotaRequest $request){
        
        $cession = CessionMagistrat::where('id_cession', $idCession)->first();
        
        $this->authorize('action', $cession);

        $data = $request->validated();

        $quota = $this->cessionBorrowerQuotaService->saveCessionBorrowerQuota(
            $data['granted_amount'],
            $idCessionBorrower
        );

        return response()->json([
            'quota' => $quota,
        ]);
    }

    public function editCessionBorrowerQuota($idCession, $idCessionBorrower, $idCessionBorrowerQuota, CessionBorrowerQuotaRequest $request)
    {
        $cession = CessionMagistrat::where('id_cession', $idCession)->first();

        $this->authorize('action', $cession);

        $data = $request->validated();

        $quota = $this->cessionBorrowerQuotaService->updateCessionBorrowerQuota(
            $idCessionBorrowerQuota,
            $idCessionBorrower,
            $data['granted_amount'],
        );

        return response()->json([
            'quota' => $quota,
        ]);
    }

    public function removeCessionBorrowerQuota($idCession, $idCessionBorrowerQuota, Request $request){
        try {

            $cession = CessionMagistrat::where('id_cession', $idCession)->first();

            $this->authorize('action', $cession);

            $this->cessionBorrowerQuotaService->deleteCessionBorrowerQuota($idCessionBorrowerQuota);

            return response([
                'message' => 'Quota supprimées avec succés',
            ]);

        } catch (Exception $ve) {
            Log::info($ve);
        }

    }
}
