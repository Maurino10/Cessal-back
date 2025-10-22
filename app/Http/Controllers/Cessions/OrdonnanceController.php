<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;

use App\Http\Requests\Cessions\CessionOrdonnanceRequest;
use App\Models\Cessions\CessionMagistrat;
use App\Services\Cessions\CessionOrdonnanceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrdonnanceController extends Controller
{


    protected $cessionOrdonnanceService;
    public function __construct(CessionOrdonnanceService $cessionOrdonnanceService)
    {

        $this->cessionOrdonnanceService = $cessionOrdonnanceService;
    }

    public function storeCessionOrdonnance($idCession, CessionOrdonnanceRequest $request)
    {
        try {
            $cession = CessionMagistrat::where('id_cession', $idCession)->first();

            $this->authorize('action', $cession);

            $data = $request->validated();

            $ordonnance = $this->cessionOrdonnanceService->saveCessionOrdonnance(
                $data['numero_ordonnance'],
                $idCession
            );

            return response()->json([
                'ordonnance' => $ordonnance
            ]);
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }

    public function editCessionOrdonnance($idCession, $idCessionOrdonnance, CessionOrdonnanceRequest $request)
    {
        try {
            $cession = CessionMagistrat::where('id_cession', $idCession)->first();

            $this->authorize('action', $cession);

            $data = $request->validated();

            $ordonnance = $this->cessionOrdonnanceService->updateCessionOrdonnance(
                $idCessionOrdonnance,
                $idCession,
                $data['numero_ordonnance'],
            );

            return response()->json([
                'ordonnance' => $ordonnance
            ]);
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }

    public function removeCessionOrdonnance($idCession, $idCessionOrdonnance, Request $request)
    {
        try {

            $cession = CessionMagistrat::where('id_cession', $idCession)->first();

            $this->authorize('action', $cession);


            $this->cessionOrdonnanceService->deleteCessionOrdonnance($idCessionOrdonnance);


            return response([
                'message' => 'Ordonnance supprimées avec succés'
            ]);

        } catch (Exception $ve) {
            Log::info($ve);
        }

    }

}