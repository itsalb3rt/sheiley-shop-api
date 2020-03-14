<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 7:23 PM
 */

namespace App\models\Miscellany;


use System\Model;

class Miscellany extends Model
{
    //Getters
    public function namesEstablishments($idPurchase){
        return $this->db()
            ->table('names_establishments')
            ->where('id_purchase','=',$idPurchase)
            ->get();
    }

    public function getItbis($idUser){
        return $this->db()
            ->table('itbis')
            ->where('id_user','=',$idUser)
            ->get();
    }

    public function countProductsWithMeasurementUnits($idMeasurementUnit){
        return $this->db()
            ->count('id_category','count')
            ->table('products')
            ->where('id_unit_measurement','=',$idMeasurementUnit)
            ->get();
    }

    public function measurementUnits($id_user){
        return $this->db()
            ->table('measurement_units')
            ->where('id_user','=',$id_user)
            ->getAll();
    }

    //Creaters

    public function addMeasurementUnits($data){
        $this->db()
            ->table('measurement_units')
            ->insert($data);
        return $this->db()->insertId();
    }



    public function createEstablishmentName($data){
        $this->db()
            ->table('names_establishments')
            ->insert($data);
    }

    public function createInitialItbis($data){
        $this->db()
            ->table('itbis')
            ->insert($data);
    }

    //Update
    public function updateItbis($idUser,$data){
        $this->db()
            ->table('itbis')
            ->where('id_user','=',$idUser)
            ->update($data);
    }

    //Delete

    public function deleteMeasurementUnits($id_unit_measurement){
        $this->db()
            ->table('measurement_units')
            ->where('id_unit_measurement','=',$id_unit_measurement)
            ->delete();
    }

    public function deleteEstablishmentName($idPurchase){
        $this->db()
            ->table('names_establishments')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }
}