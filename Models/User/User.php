<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 2/6/2019
 * Time: 7:45 PM
 */

namespace Models\User;


use Core\Model;

class User extends Model
{
    /**
     * @param $user | Array
     * @return int
     */
    public function registerUser(Array $user):int {
        $this->getBdd()
            ->table('users')
            ->insert($user);
        return $this->getBdd()->insertId();
    }

    public function isExitsUserName(String $userName):object {
        return $this->getBdd()
            ->count('id_user','count')
            ->table('users')
            ->where('user_name','=',$userName)
            ->get();
    }

    public function isExitsEmail(String $email):object {
        return $this->getBdd()
            ->count('id_user','count')
            ->table('users')
            ->where('email','=',$email)
            ->get();
    }

    public function getUser($userName){
        return $this->getBdd()
            ->table('users')
            ->where('user_name','=',$userName)
            ->get();
    }

    public function getUserById($id){
        return $this->getBdd()
        ->select('id_user,user_name,first_name,last_name,email')
            ->table('users')
            ->where('id_user','=',$id)
            ->get();
    }

    public function updateUser($id,$data){
        $this->getBdd()->table('users')->where('id_user','=',$id)->update($data);
    }

}