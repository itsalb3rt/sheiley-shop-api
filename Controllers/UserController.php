<?php

use Models\User\User;

class UserController extends Controller
{
    public function __construct()
    {
        if (ENVIROMENT == 'dev') {
            $origin = "http://localhost:8080";
        } else {
            $origin = "https://gibucket.a2hosted.com";
        }

        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Access-Control-Allow-Origin:$origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    }

    public function user($id = null)
    {
        $request = $this->easy_request();

        switch ($request->server->get('REQUEST_METHOD')) {
            case 'GET':
                $user = new User();
                $user = $user->getUserById($id);

                if (empty($user)) {
                    echo json_encode(['message' => 'user not found']);
                } else {
                    http_response_code(200);
                    echo json_encode($user);
                }
                break;
            case 'PATCH':
                $JSONData = file_get_contents("php://input");
                $user = json_decode($JSONData);

                $updateData = [
                    "first_name"=>$user->firstName,
                    "last_name"=>$user->lastName,
                    "email"=>$user->email
                ];
                
                if(!empty($user->password)){
                    if($this->passwordMatch($user->password,$user->password2)){
                        $updateData['password'] = $this->encrypt_password($user->password);
                    }else{
                        http_response_code(409);
                        echo json_encode(['message' => 'password not match']);
                        exit();
                    }
                }
                
                $userModel = new User();
                $userModel->updateUser($user->id_user,$updateData);
                echo json_encode(['message' => 'success']);
                http_response_code(200);
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
