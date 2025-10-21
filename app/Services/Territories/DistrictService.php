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

    public function findAllDistrict() {
        return District::with(['region.province'])->get();
    }

    public function findDistrict($idDistrict) {
        return District::findOrFail($idDistrict);
    }

    public function filterDistrict($word, $idProvince, $idRegion) {
        $query = District::with(['region.province']);

        if ($word !== 'null' && !empty($word)) {
            $query = $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($word) .'%']);
        }

        if ($idProvince !== 'null' && !empty($idProvince)) {
            $query = $query->where('id_province', $idProvince);
        }

        if ($idRegion !== 'null' && !empty($idRegion)) {
            $query = $query->where('id_region', $idRegion);
        }


        return $query->get();
    }
}