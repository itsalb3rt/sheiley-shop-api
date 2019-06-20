<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 7:23 PM
 */

namespace Models\Miscellany;


use Core\Model;

class Miscellany extends Model
{
    public function measurementUnits($id_user){
        return $this->getBdd()
            ->table('measurement_units')
            ->where('id_user','=',$id_user)
            ->getAll();
    }

    public function addMeasurementUnits($data){
        $this->getBdd()
            ->table('measurement_units')
            ->insert($data);
        return $this->getBdd()->insertId();
    }

    public function deleteMeasurementUnits($id_unit_measurement){
        $this->getBdd()
            ->table('measurement_units')
            ->where('id_unit_measurement','=',$id_unit_measurement)
            ->delete();
    }

    public function countProductsWithMeasurementUnits($idMeasurementUnit){
        return $this->getBdd()
            ->count('id_category','count')
            ->table('products')
            ->where('id_unit_measurement','=',$idMeasurementUnit)
            ->get();
    }

    public function categories($id_user){
        return $this->getBdd()
            ->table('categories')
            ->where('id_user','=',$id_user)
            ->getAll();
    }

    public function addCategory($data){
        $this->getBdd()
            ->table('categories')
            ->insert($data);
        return $this->getBdd()->insertId();
    }

    public function deleteCategory($idCategory){
        $this->getBdd()
            ->table('categories')
            ->where('id_category','=',$idCategory)
            ->delete();
    }

    public function countProductsWithCategory($idCategory){
        return $this->getBdd()
            ->count('id_category','count')
            ->table('products')
            ->where('id_category','=',$idCategory)
            ->get();
    }
}