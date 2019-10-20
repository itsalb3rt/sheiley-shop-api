<?php


namespace App\models\AccountRecovery;


use System\Model;

class AccountRecovery extends Model
{

    /**
     * @param $data
     * [
     *  "id_user"=>1,
     *  "single_use_token"=> 'some'
     * ]
     */
    public function setAccountRecoveryInformation($data){
        $this->db()
            ->table('recovered_accounts')
            ->insert($data);
    }

    public function removeAccountRecoveryInformation($idUser){
        $this->db()
            ->table('recovered_accounts')
            ->where('id_user','=',$idUser)
            ->delete();
    }

    public function getByToken($token){
        return $this->db()
            ->table('recovered_accounts')
            ->where('single_use_token','=',$token)
            ->get();
    }
}