<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 7:22 PM
 */
use App\models\Miscellany\Miscellany;
use Symfony\Component\HttpFoundation\Request;

class MiscellanyController extends Controller
{
    public function __construct()
    {
        if(ENVIROMENT == 'dev'){
            $origin = "http://localhost:8080";
        }else{
            $origin = "https://gibucket.a2hosted.com";
        }
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Access-Control-Allow-Origin:$origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    }

    public function categories($idUser){
         $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->categories($idUser));
        }
    }

    public function itbis(){
         $request = Request::createFromGlobals();

        switch ($request->server->get('REQUEST_METHOD')){
            case 'GET':
                $idUser = $request->query->filter('id_user');
                $miscellany = new Miscellany();
                echo json_encode($miscellany->getItbis($idUser));
                break;
            case 'POST':
                $miscellany = new Miscellany();
                $miscellany->updateItbis($request->request->filter('id_user'),
                    [
                        'quantity'=>$request->request->filter('quantity')
                    ]
                );
                echo json_encode(['status'=>'success']);
                break;
        }
    }

}