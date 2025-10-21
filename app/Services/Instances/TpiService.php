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

    public function findAllTPI($withRelations) {
        if ($withRelations) {
            return Tpi::with(['ca', 'district.region.province'])->get();
        } else {
            return Tpi::all();
        }
    }

    public function findTPI($idTPI) {
        return Tpi::findOrFail($idTPI);
    }

    public function filterTPI($word, $idProvince, $idCA, $idRegion, $idDistrict) {
        $query = TPI::with(['ca', 'district.region.province']);

        if ($word !== 'null' && !empty($word)) {
            $query = $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($word) .'%']);
        }

        if ($idProvince !== 'null' && !empty($idProvince)) {
            $query = $query->where('id_province', $idProvince);
        }

        if ($idCA !== 'null' && !empty($idCA)) {
            $query = $query->where('id_ca', $idCA);
        }

        if ($idRegion !== 'null' && !empty($idRegion)) {
            $query = $query->where('id_region', $idRegion);
        }

        if ($idDistrict !== 'null' && !empty($idDistrict)) {
            $query = $query->where('id_district', $idDistrict);
        }


        return $query->get();
    }
}