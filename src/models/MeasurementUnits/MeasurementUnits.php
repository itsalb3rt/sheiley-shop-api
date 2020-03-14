<?php


namespace App\models\MeasurementUnits;


use System\Model;

class MeasurementUnits extends Model
{
    public function getAll($idUser)
    {
        return $this->db()
            ->table('measurement_units')
            ->where('id_user', '=', $idUser)
            ->getAll();
    }

    public function getById($id,$idUser)
    {
        return $this->db()
            ->table('measurement_units')
            ->where('id_unit_measurement = ? AND id_user = ?', [$id,$idUser])
            ->get();
    }

    public function countProductsWithMeasurementUnits($idMeasurementUnit)
    {
        return $this->db()
            ->count('id_category', 'count')
            ->table('products')
            ->where('id_unit_measurement', '=', $idMeasurementUnit)
            ->get();
    }

    public function create($data)
    {
        $this->db()
            ->table('measurement_units')
            ->insert($data);
        return $this->db()->insertId();
    }


    public function delete($idMeasurementUnit)
    {
        $this->db()
            ->table('measurement_units')
            ->where('id_unit_measurement', '=', $idMeasurementUnit)
            ->delete();
    }

}