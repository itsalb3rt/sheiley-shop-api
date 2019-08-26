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
    public function products($idUser = null,$idProduct = null){
        if($idUser == null){
            return $this->db()
                ->table('products')
                ->where('id_product','=',$idProduct)
                ->get();
        }else{
            return $this->db()
                ->table('products')
                ->where('id_user','=',$idUser)
                ->getAll();
        }
    }

    public function add($data){
        $this->db()
            ->table('products')
            ->insert($data);
        return $this->db()->insertId();
    }

    public function update($idProduct,$data){
        $this->db()
            ->table('products')
            ->where('id_product','=',$idProduct)
            ->update($data);
    }

    public function delete($idProduct){
        $this->db()
            ->table('products')
            ->where('id_product','=',$idProduct)
            ->delete();
    }

    public function getProductByName($name,$idUser){
        return $this->db()
            ->count('id_product','count')
            ->table('products')
            ->where('name = ? AND id_user = ?',[$name,$idUser])
            ->get();
    }
}