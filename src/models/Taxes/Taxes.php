<?php


namespace App\models\Taxes;


use System\Model;

class Taxes extends Model
{

    public function get($idUser){
        return $this->db()
            ->table('taxes')
            ->where('id_user','=',$idUser)
            ->get();
    }

    public function update($idUser,$data){
        $this->db()
            ->table('taxes')
            ->where('id_user','=',$idUser)
            ->update($data);
    }
}