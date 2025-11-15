<?php

namespace App\Services\Instances;

use App\Models\Instances\Ca;
use Illuminate\Support\Facades\Log;


class CaService {
    public function saveCA($name, $idProvince) {
        return Ca::create([
            "name"=> $name,
            "id_province" => $idProvince
        ]);
    }

    public function updateCA($idCA, $name, $idProvince) {
        return Ca::where("id", $idCA)->update([
            "name"=> $name,
            "id_province" => $idProvince
        ]);
    }

    public function deleteCA($idCA) {
        return Ca::where("id", $idCA)->delete();
    }

    public function findAllCA($withRelations, $search) {

        if($withRelations) {
            $query = Ca::with('province');
    
            if ($search !== 'null' && $search !== null && !empty($search)) {
                $query->where('name', 'ILIKE', "%$search%")
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%$search%");
                    });
            }
    
            return $query
                ->orderBy('name')
                ->paginate(10);
        } else {
            return Ca::orderBy('name')->get();
        }
    }

    public function findCA($idCA) {
        return Ca::findOrFail($idCA);
    }

}