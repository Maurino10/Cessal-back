<?php

namespace App\Services\Instances;

use App\Models\Instances\Ca;


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

    public function findAllCA() {
        return Ca::with('province')->get();
    }

    public function findCA($idCA) {
        return Ca::findOrFail($idCA);
    }

    public function filterCA($word, $idProvince) {
        $query = Ca::with('province');

        if ($word !== 'null' && !empty($word)) {
            $query = $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($word) .'%']);
        }

        if ($idProvince !== 'null' && !empty($idProvince)) {
            $query = $query->where('id_province', $idProvince);
        }

        return $query->get();
    }
}