<?php

/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 19/7/2019
 * Time: 1:13 PM
 */

namespace App\plugins;

use App\models\Users\Users;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SecureApi
{
    private $publicArea;
    private $request;
    private $acceptOrigin = "*";

    public function __construct(Request $request , bool $publicArea = false)
    {
        $this->publicArea = $publicArea;
        $this->request = $request;

        $this->cors();

        if ($this->publicArea === false) {
            $this->isTokenValid();
        }
    }

    private function cors(): void
    {
        if ($this->request->server->get('REQUEST_METHOD') == 'OPTIONS') {
            header("Access-Control-Allow-Credentials", "true");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
            header("Access-Control-Allow-Origin:$this->acceptOrigin");
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            die();
        }

        header("Access-Control-Allow-Credentials", "true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header("Access-Control-Allow-Origin:$this->acceptOrigin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    }

    private function isTokenValid(): void
    {
        try {
            $token = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
            $decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET"], 'HS256'));
            $user = new Users();
            $user = $user->getById($decoded->id_user);
            $this->request->attributes->set('user', $user);
        } catch (\Exception $e) {
            new RestResponse(null, 401, 'unauthorized');
            die();
        }
    }
}
