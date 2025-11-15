<?php

namespace App\Http\Controllers\Territories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Territories\DistrictRequest;
use App\Services\Territories\DistrictService;
use Illuminate\Http\Request;


class DistrictController extends Controller
{
    protected $districtService;
    public function __construct(DistrictService $districtService) {

        $this->districtService = $districtService;
    
    }
    
// ------------------------------- ------------------------------- ------------------------------- Store

    public function storeDistrict(DistrictRequest $request) {
        $data = $request->validated();

        $district = $this->districtService->saveDistrict(
            $data["name"], 
            $data["region"]
        );

        return response()->json([
            "district"=> $district,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Edit

    public function editDistrict(DistrictRequest $request, $idDistrict) {
        $data = $request->validated();


        $district = $this->districtService->updateDistrict(
            $idDistrict,
            $data["name"],
            $data["region"]
        );

        return response()->json([
            "district"=> $district,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Remove

    public function removeDistrict($idDistrict) {

        $this->districtService->deleteDistrict($idDistrict);

        return response()->json([
            "message" => "Le district a été supprimé",
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get

    public function getDistrict($idDistrict) {

        $district = $this->districtService->findDistrict($idDistrict);

        return response()->json([
            "district" => $district,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get All

    public function getAllDistrict(Request $request) {

        $search = $request->input('search');
        $idProvince = $request->input('province');
        $idRegion = $request->input('region');

        $districts = $this->districtService->findAllDistrict(true, $search, $idProvince, $idRegion);

        return response()->json([
            "districts"=> $districts,
        ]);
    }

    public function getAllDistrictWithoutRelations(Request $request) {

        $districts = $this->districtService->findAllDistrict(false, null, null, null);

        return response()->json([
            "districts"=> $districts,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Filter

}
