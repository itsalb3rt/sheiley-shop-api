<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 22/6/2019
 * Time: 4:56 PM
 */

namespace App\models\Shoppings;


use System\Model;

class Shoppings extends Model
{

    public function getAll($idUser){
        return $this->db()
            ->query("SELECT purchases.id_purchase,names_establishments.name AS names_establishments, purchases.create_at,
                (
                SELECT
                    SUM(
                        ((purchases_details.unit_price * purchases_details.quantity) * (taxes.quantity/100)) + (purchases_details.unit_price * purchases_details.quantity)
                    ) AS total
                FROM
                    purchases_details
                    INNER JOIN taxes ON taxes.id_user = ?
                WHERE
                    purchases_details.id_purchase = purchases.id_purchase
                ) AS total
                FROM purchases
                INNER JOIN names_establishments ON names_establishments.id_purchase = purchases.id_purchase
                WHERE purchases.id_user = ?
                ORDER BY create_at DESC",[$idUser,$idUser])
            ->fetchAll();
    }

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

    public function deletePurchase($idPurchase,$idUser){
        $this->db()
            ->table('purchases')
            ->where('id_purchase = ? AND id_user = ?',[$idPurchase,$idUser])
            ->delete();
    }

    public function deletePurchaseDetails($idPurchase){
        $this->db()
            ->table('purchases_details')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }
}