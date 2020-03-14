<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 7:23 PM
 */

namespace App\models\Miscellany;


use System\Model;

class Miscellany extends Model
{
    //Getters
    public function namesEstablishments($idPurchase){
        return $this->db()
            ->table('names_establishments')
            ->where('id_purchase','=',$idPurchase)
            ->get();
    }

    public function getItbis($idUser){
        return $this->db()
            ->table('itbis')
            ->where('id_user','=',$idUser)
            ->get();
    }

    //Creaters

    public function createEstablishmentName($data){
        $this->db()
            ->table('names_establishments')
            ->insert($data);
    }

    public function createInitialItbis($data){
        $this->db()
            ->table('itbis')
            ->insert($data);
    }

    //Update
    public function updateItbis($idUser,$data){
        $this->db()
            ->table('itbis')
            ->where('id_user','=',$idUser)
            ->update($data);
    }

    //Delete

    public function deleteEstablishmentName($idPurchase){
        $this->db()
            ->table('names_establishments')
            ->where('id_purchase','=',$idPurchase)
            ->delete();
    }
}