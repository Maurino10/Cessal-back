<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Services\Cessions\CessionMagistratService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MagistratController extends Controller {

    protected $cessionMagistratService;

    public function __construct(CessionMagistratService $cessionMagistratService) {
        $this->cessionMagistratService = $cessionMagistratService;
    }


// ------------------------------- ------------------------------- ------------------------------- Cession Assignment

    public function storeCessionMagistrat ($idCession, Request $request) {
        try {
            
            $cession = Cession::findOrFail($idCession);

            $this->authorize('store', $cession);

            $data = $request->validate([
                'magistrat' => 'required|string',
            ]);

            $magistrat = $this->cessionMagistratService->saveCessionMagistrat($data['magistrat'], $idCession);

            return response()->json([
                'magistrat' => $magistrat
            ]);
        } catch (ValidationException $e) {

        }
    }

    public function editCessionMagistrat ($idCession, $idCessionMagistrat, Request $request) {
        try {
            
            $cession = Cession::findOrFail($idCession);

            $this->authorize('store', $cession);

            $data = $request->validate([
                'magistrat' => 'required|string',
            ]);

            $magistrat = $this->cessionMagistratService->updateCessionMagistrat(
                $idCessionMagistrat,
                $data['magistrat'], 
            );

            return response()->json([
                'magistrat' => $magistrat
            ]);
        } catch (ValidationException $e) {

        }
    }

    
    public function getMagistratByCession($idCession) {

        $magistrat = $this->cessionMagistratService->findMagistratByCession($idCession);

        return response()->json([
            'magistrat' => $magistrat
        ]);
    }
    public function getAllCessionByMagistrat($idUser, Request $request)
    {
        $this->authorize('viewAny', CessionMagistrat::class);

        $cessions = $this->cessionMagistratService->findAllCessionByMagistrat($idUser);

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function getAllMagistratByTpi($idTPI) {

        $magistrats = $this->cessionMagistratService->findAllMagistratByTpi($idTPI);

        return response()->json([
            "magistrats" => $magistrats 
        ]);

    }
}  