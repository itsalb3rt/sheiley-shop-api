<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 23/6/2019
 * Time: 7:59 PM
 */
use Models\Analysis\Analysis;

class AnalysisController extends Controller
{
    public function __construct()
    {
        $allowedOrigins = [
            "http://localhost:8080",
            "https://gibucket.a2hosted.com"
        ];

        if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
            header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        }
    }

    public function shoppingHistory(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $idUser = $request->query->filter('id_user');
            $history = new Analysis();
            echo json_encode([
                'status'=>'success',
                'history'=>$history->shoppingHistory($idUser)
            ]);
        }
    }
}