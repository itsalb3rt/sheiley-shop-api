<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 2/6/2019
 * Time: 7:45 PM
 */

namespace App\models\User;

use System\Model;

class User extends Model
{
    /**
     * @param $user | Array
     * @return int
     */
    public function registerUser(Array $user):int {
        $this->db()
            ->table('users')
            ->insert($user);
        return $this->db()->insertId();
    }

    public function isExitsUserName(String $userName):object {
        return $this->db()
            ->count('id_user','count')
            ->table('users')
            ->where('user_name','=',$userName)
            ->get();
    }

    public function isExitsEmail(String $email):object {
        return $this->db()
            ->count('id_user','count')
            ->table('users')
            ->where('email','=',$email)
            ->get();
    }

    public function getUser($userName){
        return $this->db()
            ->table('users')
            ->where('user_name','=',$userName)
            ->get();
    }

    public function getUserById($id){
        return $this->db()
        ->select('id_user,user_name,first_name,last_name,email')
            ->table('users')
            ->where('id_user','=',$id)
            ->get();
    }

    public function updateUser($id,$data){
        $this->db()->table('users')->where('id_user','=',$id)->update($data);
    }

}