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
    public function measurementUnits(){
        return $this->getBdd()
            ->table('measurement_units')
            ->getAll();
    }

    public function categories(){
        return $this->getBdd()
            ->table('categories')
            ->getAll();
    }
}