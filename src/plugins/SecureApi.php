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

class SecureApi
{
    private $publicArea;
    private $request;
    private $acceptOrigin = (ENVIROMENT == 'dev') ? "*" : "https://gibucket.a2hosted.com";

    public function __construct(bool $publicArea = false)
    {
        $this->publicArea = $publicArea;
        $this->request = Request::createFromGlobals();

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
        $userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
        $user = new Users();
        $user = $user->getByToken($userToken);

        if (empty($user)) {
            new RestResponse(null ,403,'Invalid token');
            die();
        }
    }
}
