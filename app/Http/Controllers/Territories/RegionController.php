<?php

namespace App\Http\Controllers\Territories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Territories\RegionRequest;
use App\Services\Territories\RegionService;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    protected $regionService;
    public function __construct(RegionService $regionService) {

        $this->regionService = $regionService;
    
    }
    
// ------------------------------- ------------------------------- ------------------------------- Store
    public function storeRegion(RegionRequest $request) {
        $data = $request->validated();

        $region = $this->regionService->saveRegion(
            $data["name"], 
            $data["province"]
        );

        return response()->json([
            "region"=> $region,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Edit

    public function editRegion(RegionRequest $request, $idRegion) {
        $data = $request->validated();


        $region = $this->regionService->updateRegion(
            $idRegion, 
            $data["name"], 
            $data["province"]
        );

        return response()->json([
            "region"=> $region,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Remove
    public function removeRegion($idRegion) {

        $this->regionService->deleteRegion($idRegion);

        return response()->json([
            "message" => "La region a été supprimée",
        ]);
    }
    
// ------------------------------- ------------------------------- ------------------------------- Get

    public function getRegion($idRegion) {

        $region = $this->regionService->findRegion($idRegion);

        return response()->json([
            "region" => $region,
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Get All

    public function getAllRegion(Request $request) {
        
        $search = $request->input('search');
        $idProvince = $request->input('province');

        $regions = $this->regionService->findAllRegion(true, $search, $idProvince);

        return response()->json([
            "regions"=> $regions,
        ]);
    }

    public function getAllRegionWithoutRelations() {

        $regions = $this->regionService->findAllRegion(false,null, null);

        return response()->json([
            "regions"=> $regions,
        ]);

    }
}
