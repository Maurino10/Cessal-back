<?php

namespace App\Services\Territories;

use App\Models\Territories\Province;


class ProvinceService {
    public function saveProvince($name) {
        return Province::create([
            "name"=> $name,
        ]);
    }

    public function updateProvince($idProvince, $name) {
        return Province::where("id", $idProvince)->update([
            "name"=> $name,
        ]);
    }

    public function deleteProvince($idProvince) {
        return Province::where("id", $idProvince)->delete();
    }

    public function findAllProvince($withRelations, $search) {
        if($withRelations) {
    
            if ($search !== 'null' && $search !== null && !empty($search)) {
                $query = Province::where('name', 'ILIKE', "%$search%");
                return $query
                    ->orderBy('name')
                    ->paginate(10);
            } else {
                return Province::orderBy('name')->paginate(10);
            }
    
        } else {
            return Province::orderBy('name')->get();
        }
    }

    public function findProvince($idProvince) {
        return Province::findOrFail($idProvince);
    }
}