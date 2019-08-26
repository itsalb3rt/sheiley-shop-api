<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 23/6/2019
 * Time: 8:03 PM
 */

namespace App\models\Analysis;


use System\Model;

class Analysis extends Model
{
    public function shoppingHistory($idUser){
        return $this->db()
            ->query("SELECT purchases.id_purchase,names_establishments.name AS names_establishments, purchases.date,
(
SELECT
    SUM(
        ((purchases_details.unit_price * purchases_details.quantity) * (itbis.quantity/100)) + (purchases_details.unit_price * purchases_details.quantity)
    ) AS total
FROM
    purchases_details
    INNER JOIN itbis ON itbis.id_user = ?
WHERE
    purchases_details.id_purchase = purchases.id_purchase
) AS total
FROM purchases
INNER JOIN names_establishments ON names_establishments.id_purchase = purchases.id_purchase
WHERE purchases.id_user = ?
ORDER BY date DESC",[$idUser,$idUser])
            ->fetchAll();
    }
}