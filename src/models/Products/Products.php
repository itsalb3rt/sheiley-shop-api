<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 5:52 PM
 */

namespace App\models\Products;


use System\Model;

class Products extends Model
{
    public function getAll($idUser)
    {
        return $this->db()
            ->table('products')
            ->where('id_user', '=', $idUser)
            ->orderBy('name','ASC')
            ->getAll();
    }

    public function getAllBrands($idUser)
    {
        return $this->db()
            ->select('brand')
            ->table('products')
            ->groupBy('brand')
            ->where('id_user', '=', $idUser)
            ->where('brand','!=', 'NULL')
            ->orderBy('brand','ASC')
            ->getAll();
    }

    public function getById($idUser, $idProduct)
    {
        return $this->db()
            ->table('products')
            ->where('id_product = ? AND id_user = ?', [$idProduct, $idUser])
            ->get();
    }

    public function getPurchases($idProduct)
    {
        return $this->db()
            ->table('purchases_details')
            ->where('id_product = ?', [$idProduct])
            ->getAll();
    }

    public function add($data)
    {
        $this->db()
            ->table('products')
            ->insert($data);
        return $this->db()->insertId();
    }

    public function update($idProduct, $data)
    {
        $this->db()
            ->table('products')
            ->where('id_product', '=', $idProduct)
            ->update($data);
    }

    public function delete($idProduct, $idUser)
    {
        $this->db()
            ->table('products')
            ->where('id_product = ? AND id_user = ?', [$idProduct, $idUser])
            ->delete();
    }

    public function getProductByNameAndBrand($name, $brand, $idUser)
    {
        return $this->db()
            ->count('id_product', 'count')
            ->table('products')
            ->where('name = ? AND brand = ? AND id_user = ?', [$name, $brand, $idUser])
            ->get();
    }
}