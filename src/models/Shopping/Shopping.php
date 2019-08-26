<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 22/6/2019
 * Time: 4:56 PM
 */

namespace App\models\Shopping;


use System\Model;

class Shopping extends Model
{
    public function createPurchase($data){
        $this->db()
            ->table('purchases')
            ->insert($data);
        return $this->db()->insertId();
    }

    public function createDetailPurchase($data){
        $this->db()
            ->table('purchases_details')
            ->insert($data);
    }

    public function purchaseDetails($idPurchase){
        return $this->db()
            ->table('purchases_details')
            ->where('id_purchase','=',$idPurchase)
            ->getAll();
    }

    public function purchase($idPurchase){
        return $this->db()
            ->table('purchases')
            ->where('id_purchase','=',$idPurchase)
            ->get();
    }

    public function deletePurchase($idPurchase){
        $this->db()
            ->table('purchases')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }

    public function deletePurchaseDetails($idPurchase){
        $this->db()
            ->table('purchases_details')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }
}