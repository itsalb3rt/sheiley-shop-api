<?php

use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    private $request;
    private $userToken;

    public function __construct()
    {
        new SecureApi();
        $this->request = Request::createFromGlobals();
        $this->userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
    }

    public function users($id = null)
    {
        $request = Request::createFromGlobals();
        $users = new Users();

        switch ($request->server->get('REQUEST_METHOD')) {
            case 'GET':
                if($id !== null){
                    new RestResponse($users->getById($id));
                    return;
                }
                break;
            case 'PATCH':
                $user = json_decode($this->request->getContent());

                $updateData = [
                    "first_name"=>$user->firstName,
                    "last_name"=>$user->lastName,
                    "email"=>$user->email
                ];
                
                if(!empty($user->password)){
                    if($this->passwordMatch($user->password,$user->password2)){
                        $updateData['password'] = $this->encrypt_password($user->password);
                    }else{
                        new RestResponse([],409,"password not match");
                        return;
                    }
                }

                $users->update($user->id_user,$updateData);
                new RestResponse($users->getById($id),200,"updated");
                break;
        }
    }

    private function passwordMatch($password1,$password2){
        if($password1 == $password2){
            return true;
        }else{
            return false;
        }
    }

    private function encrypt_password($password){
        return password_hash($password,PASSWORD_ARGON2I);
    }
}
