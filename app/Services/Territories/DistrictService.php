<?php

namespace App\Services\Territories;

use App\Models\Territories\District;


class DistrictService {
    public function saveDistrict($name, $idRegion) {
        return District::create([
            "name"=> $name,
            "id_region"=> $idRegion
        ]);
    }

    public function updateDistrict($idDistrict, $name, $idRegion) {
        return District::where("id", $idDistrict)->update([
            "name"=> $name,
            "id_region"=> $idRegion
        ]);
    }

    public function deleteDistrict($idDistrict) {
        return District::where("id", $idDistrict)->delete();
    }

    public function findAllDistrict($withRelations, $search, $idProvince, $idRegion) {

        if ($withRelations) {
            $query = District::with('region.province');

            if ($search !== 'null' && $search !== null &&  !empty($search)) {
                $query = $query->where('name', 'ILIKE', "%$search%");
            }

            if ($idProvince !== 'null' && $idProvince !== null && !empty($idProvince)) {
                $query = $query->whereHas('region', function ($q) use ($idProvince) {
                    $q->where('id_province', $idProvince);
                });
            }

            if ($idRegion !== 'null' && $idRegion !== null && !empty($idRegion)) {
                $query = $query->where('id_region', $idRegion);
            }

            return $query
                ->orderBy('name')
                ->paginate(10);
        } else {
            return District::with(['region.province'])->orderBy('name')->get();
        }
    }

    public function findDistrict($idDistrict) {
        return District::findOrFail($idDistrict);
    }

}