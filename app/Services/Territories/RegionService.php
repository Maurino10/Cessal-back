<?php

namespace App\Services\Territories;

use App\Models\Territories\Region;


class RegionService {
    public function saveRegion($name, $idProvince) {
        return Region::create([
            "name"=> $name,
            "id_province" => $idProvince
        ]);
    }

    public function updateRegion($idRegion, $name, $idProvince) {
        return Region::where("id", $idRegion)->update([
            "name"=> $name,
            "id_province" => $idProvince
        ]);
    }

    public function deleteRegion($idRegion) {
        return Region::where("id", $idRegion)->delete();
    }

    public function findAllRegion() {
        return Region::with('province')->get();
    }

    public function findRegion($idRegion) {
        return Region::findOrFail($idRegion);
    }

    public function filterRegion($word, $idProvince) {
        $query = Region::with('province');

        if ($word !== 'null' && !empty($word)) {
            $query = $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($word) .'%']);
        }

        if ($idProvince !== 'null' && !empty($idProvince)) {
            $query = $query->where('id_province', $idProvince);
        }

        return $query->get();
    }
}