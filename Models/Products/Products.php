<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 5:52 PM
 */

namespace Models\Products;


use Core\Model;

class Products extends Model
{
    public function products($idUser){
        return $this->getBdd()
            ->table('products')
            ->where('id_user','=',$idUser)
            ->getAll();
    }

    public function add($data){
        $this->getBdd()
            ->table('products')
            ->insert($data);
        return $this->getBdd()->insertId();
    }

    public function getProductByName($name,$idUser){
        return $this->getBdd()
            ->count('id_product','count')
            ->table('products')
            ->where('name = ? AND id_user = ?',[$name,$idUser])
            ->get();
    }
}