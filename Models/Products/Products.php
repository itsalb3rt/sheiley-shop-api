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
    public function products(){
        return $this->getBdd()
            ->table('products')
            ->getAll();
    }
}