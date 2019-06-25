<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 22/6/2019
 * Time: 4:56 PM
 */

namespace Models\Shopping;


use Core\Model;

class Shopping extends Model
{
    public function createPurchase($data){
        $this->getBdd()
            ->table('purchases')
            ->insert($data);
        return $this->getBdd()->insertId();
    }

    public function createDetailPurchase($data){
        $this->getBdd()
            ->table('purchases_details')
            ->insert($data);
    }
}