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
        if(ENVIROMENT == 'dev'){
            $origin = "http://localhost:8080";
        }else{
            $origin = "https://gibucket.a2hosted.com";
        }

        header("Access-Control-Allow-Origin:$origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
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