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
    public function products($idUser = null,$idProduct = null){
        if($idUser == null){
            return $this->getBdd()
                ->table('products')
                ->where('id_product','=',$idProduct)
                ->get();
        }else{
            return $this->getBdd()
                ->table('products')
                ->where('id_user','=',$idUser)
                ->getAll();
        }
    }

    public function add($data){
        $this->getBdd()
            ->table('products')
            ->insert($data);
        return $this->getBdd()->insertId();
    }

    public function update($idProduct,$data){
        $this->getBdd()
            ->table('products')
            ->where('id_product','=',$idProduct)
            ->update($data);
    }

    public function delete($idProduct){
        $this->getBdd()
            ->table('products')
            ->where('id_product','=',$idProduct)
            ->delete();
    }

    public function getProductByName($name,$idUser){
        return $this->getBdd()
            ->count('id_product','count')
            ->table('products')
            ->where('name = ? AND id_user = ?',[$name,$idUser])
            ->get();
    }
}