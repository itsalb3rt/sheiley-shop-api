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

    public function purchaseDetails($idPurchase){
        return $this->getBdd()
            ->table('purchases_details')
            ->where('id_purchase','=',$idPurchase)
            ->getAll();
    }

    public function purchase($idPurchase){
        return $this->getBdd()
            ->table('purchases')
            ->where('id_purchase','=',$idPurchase)
            ->get();
    }

    public function deletePurchase($idPurchase){
        $this->getBdd()
            ->table('purchases')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }

    public function deletePurchaseDetails($idPurchase){
        $this->getBdd()
            ->table('purchases_details')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }
}