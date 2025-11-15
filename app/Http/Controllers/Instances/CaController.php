<?php

namespace App\Http\Controllers\Instances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instances\CaRequest;
use App\Services\Instances\CaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaController extends Controller
{
    protected $caService;
    public function __construct(CaService $caService) {

        $this->caService = $caService;
    
    }
// ------------------------------- ------------------------------- ------------------------------- Store
    
    public function storeCA(CaRequest $request) {
        $data = $request->validated();

        $ca = $this->caService->saveCA($data["name"], $data["province"]);

        return response()->json([
            "ca"=> $ca,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Edit

    public function editCA(CaRequest $request, $idCA) {
        $data = $request->validated();

        $ca = $this->caService->updateCA($idCA, $data["name"], $data["province"]);

        return response()->json([
            "ca"=> $ca,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Remove

    public function removeCA($idCA) {

        $this->caService->deleteCA($idCA);

        return response()->json([
            "message" => "La ca a été supprimée",
        ]);
    }
    
// ------------------------------- ------------------------------- ------------------------------- Get

    public function getCA($idCA) {

        $ca = $this->caService->findCA($idCA);

        return response()->json([
            "ca" => $ca,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get All

    public function getAllCA(Request $request) {

        $search = $request->input('search') ?? null;

        $ca = $this->caService->findAllCA(true, $search);

        return response()->json([
            "ca"=> $ca,
        ]);
    }

    public function getAllCAWithoutRelations(Request $request) {

        $ca = $this->caService->findAllCA(false, null);

        return response()->json([
            "ca"=> $ca,
        ]);
    }
}
