<?php

namespace App\Http\Controllers\Instances;

use App\Exports\ModelInstanceExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instances\TpiRequest;
use App\Imports\InstanceImport;
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

    public function getAllTPI(Request $request) {

        $search = $request->input('search') ?? null;
        $idCA = $request->input('ca') ?? null;

        $tpi = $this->tpiService->findAllTPI(true, $search, $idCA);

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Import

    public function importInstance(Request $request) 
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);
    
            Excel::import(new InstanceImport, $request->file('file'));
    
            return response()->json(['success', 'Fichier importé avec succès!']);
        } catch (Exception $th) {
            Log::info($th);
        }
    }


    public function exportModelInstance(Request $request) 
    {
        try {
            Log::info("Hello");
            $fileName = 'modele_instance.xlsx';
            return Excel::download(new ModelInstanceExport, $fileName);
        } catch (Exception $th) {
            Log::info($th);
        }
    }
    


// ------------------------------- ------------------------------- ------------------------------- Get All No Relations
    public function getAllTPIWithoutRelations() {
        $tpi = $this->tpiService->findAllTPI(false, null, null);

        return response()->json([
            "tpi"=> $tpi,
        ]);
    }
}
