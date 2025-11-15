<?php

namespace App\Services\Instances;

use App\Models\Instances\Tpi;
use Illuminate\Support\Facades\Log;


class TpiService {
    public function saveTPI($name, $idCA, $idDistrict) {
        return Tpi::create([
            "name"=> $name,
            "id_ca" => $idCA,
            "id_district"=> $idDistrict
        ]);
    }

    public function updateTPI($idTPI, $name, $idCA, $idDistrict) {
        return Tpi::where("id", $idTPI)->update([
            "name"=> $name,
            "id_ca" => $idCA,
            "id_district"=> $idDistrict
        ]);
    }

    public function deleteTPI($idTPI) {
        return Tpi::where("id", $idTPI)->delete();
    }

    public function findAllTPI($withRelations, $search, $idCA) {
        if ($withRelations) {
            $query = TPI::with(['ca', 'district.region.province']);

            if (!empty($search) && $search !== null) {
                $query = $query->where('name', 'ILIKE', "%$search%");
            }

            if ($idCA !== 'null' && $idCA !== null && !empty($idCA)) {
                $query = $query->where('id_ca', $idCA);
            }

            return $query
                ->orderBy('name')
                ->paginate(10);
        } else {
            return Tpi::orderBy('name')->get();
        }
    }

    public function findTPI($idTPI) {
        return Tpi::findOrFail($idTPI);
    }
}