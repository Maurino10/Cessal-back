<?php

namespace App\Http\Controllers\Instances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instances\TpiRequest;
use App\Imports\TpiImport;
use App\Services\Instances\TpiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TpiController extends Controller
{
    protected $tpiService;
    public function __construct(TpiService $tpiService) {

        $this->tpiService = $tpiService;
    
    }
    
// ------------------------------- ------------------------------- ------------------------------- Store
    public function storeTPI(TpiRequest $request) {
        $data = $request->validated();

        $tpi = $this->tpiService->saveTPI(
            $data["name"], 
            $data["ca"], 
            $data["district"], 
        );

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Edit

    public function editTPI(TpiRequest $request, $idTPI) {
        $data = $request->validated();

        $tpi = $this->tpiService->updateTPI(
            $idTPI, 
            $data["name"], 
            $data["ca"], 
            $data["district"], 
        );

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Remove

    public function removeTPI($idTPI) {

        $this->tpiService->deleteTPI($idTPI);

        return response()->json([
            "message" => "Le TPI a été supprimé",
        ]);
    }
    
// ------------------------------- ------------------------------- ------------------------------- Update

    public function getTPI($idTPI) {

        $tpi = $this->tpiService->findTPI($idTPI);

        return response()->json([
            "tpi" => $tpi,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get All

    public function getAllTPI() {

        $tpi = $this->tpiService->findAllTPI(true);

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Import

    public function importTPI(Request $request) 
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);
    
            Excel::import(new TpiImport, $request->file('file'));
    
            return response()->json(['success', 'Fichier importé avec succès!']);
        } catch (Exception $th) {
            Log::info($th);
        }
    }

// ------------------------------- ------------------------------- ------------------------------- Filter
    public function filterTPI(Request $request) {

        $word = $request->word;
        $idProvince = $request->province;
        $idCA = $request->ca;
        $idRegion = $request->region;
        $idDistrict = $request->district;

        $tpi = $this->tpiService->filterTPI($word,$idProvince, $idCA, $idRegion, $idDistrict);

        return response()->json([
            "tpi"=> $tpi,
        ]);

    }

// ------------------------------- ------------------------------- ------------------------------- Get All No Relations
    public function getAllWithoutRelations() {
        $tpi = $this->tpiService->findAllTPI(false);

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }
}
