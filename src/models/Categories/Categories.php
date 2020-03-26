<?php


namespace App\models\Categories;


use System\Model;

class Categories extends Model
{
    public function getAll($id_user){
        return $this->db()
            ->table('categories')
            ->where('id_user','=',$id_user)
            ->getAll();
    }

    public function getById($id,$id_user){
        return $this->db()
            ->table('categories')
            ->where('id_category = ? AND id_user = ?',[$id,$id_user])
            ->get();
    }

    public function create($data){
        $this->db()
            ->table('categories')
            ->insert($data);
        return $this->db()->insertId();
    }

    public function delete($idCategory){
        $this->db()
            ->table('categories')
            ->where('id_category','=',$idCategory)
            ->delete();
    }

    public function countProductsWithCategory($idCategory){
        return $this->db()
            ->count('id_category','count')
            ->table('products')
            ->where('id_category','=',$idCategory)
            ->get();
    }
}