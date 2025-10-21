<?php

namespace App\Http\Controllers\Territories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Territories\ProvinceRequest;
use App\Services\Territories\ProvinceService;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    protected $provinceService;
    public function __construct(ProvinceService $provinceService) {

        $this->provinceService = $provinceService;
    
    }
    
// ------------------------------- ------------------------------- ------------------------------- Store
    public function storeProvince(ProvinceRequest $request) {
        $data = $request->validated();

        $province = $this->provinceService->saveProvince(
            $data["name"]
        );

        return response()->json([
            "province"=> $province,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Edit

    public function editProvince(ProvinceRequest $request, $idProvince) {
        $data = $request->validated();


        $province = $this->provinceService->updateProvince(
            $idProvince, 
            $data["name"]
        );

        return response()->json([
            "province"=> $province,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Remove

    public function removeProvince($idProvince) {

        $this->provinceService->deleteProvince($idProvince);

        return response()->json([
            "message" => "La province a été supprimée",
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get
    
    public function getProvince($idProvince) {

        $province = $this->provinceService->findProvince($idProvince);

        return response()->json([
            "province" => $province,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get All

    public function getAllProvince() {

        $provinces = $this->provinceService->findAllProvince();

        return response()->json([
            "provinces"=> $provinces,
        ]);
    }

}
