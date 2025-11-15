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

    public function findAllRegion($withRelations, $search, $idProvince) {

        if ($withRelations) {
            $query = Region::with('province');

            if ($search !== 'null' && $search !== null &&  !empty($search)) {
                $query = $query->where('name', 'ILIKE', "%$search%");
            }

            if ($idProvince !== 'null' && $idProvince !== null && !empty($idProvince)) {
                $query = $query->where('id_province', $idProvince);
            }

            return $query
                ->orderBy('name')
                ->paginate(10);
        } else {
            return Region::with('province')->orderBy('name')->get();
        }
    }

    public function findRegion($idRegion) {
        return Region::findOrFail($idRegion);
    }

}