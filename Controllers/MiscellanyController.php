<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 18/6/2019
 * Time: 7:22 PM
 */
use Models\Miscellany\Miscellany;

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


    public function measurementUnits($id_user){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->measurementUnits($id_user));
        }
    }

    public function addMeasurementUnit(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'POST'){

            $miscellany = new Miscellany();
            $insertId = $miscellany->addMeasurementUnits([
                'name'=>$request->request->get('name'),
                'id_user'=>$request->request->get('id_user'),
            ]);
            echo json_encode([
                'status'=>'success',
                'data'=>[
                    'id'=>$insertId,
                    'name'=>$request->request->get('name')
                ]
            ]);
        }
    }

    public function deleteMeasurementUnit($idUnitMeasurement = null){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET' && $idUnitMeasurement != null){
            $miscellany = new Miscellany();
            $result = null;
            if($this->measurementUnitHasDependency($idUnitMeasurement)){
                $result = ['status'=>'hasDependency'];
            }else{
                $miscellany->deleteMeasurementUnits($idUnitMeasurement);
                $result = ['status'=>'success'];
            }

            echo json_encode($result);
        }
    }

    private function measurementUnitHasDependency(int $idMeasurementUnit):bool {
        $miscellany = new Miscellany();
        $result = $miscellany->countProductsWithMeasurementUnits($idMeasurementUnit);
        if($result->count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function categories($idUser){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->categories($idUser));
        }
    }

    public function addCategory(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'POST'){

            $miscellany = new Miscellany();
            $insertId = $miscellany->addCategory([
                'name'=>$request->request->get('name'),
                'id_user'=>$request->request->get('id_user'),
            ]);
            echo json_encode([
                'status'=>'success',
                'data'=>[
                    'id'=>$insertId,
                    'name'=>$request->request->get('name')
                ]
            ]);
        }
    }

    public function deleteCategory($idCategory = null):void {
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET' && $idCategory != null){
            $miscellany = new Miscellany();
            $result = null;
            if($this->categoryhasDependency($idCategory)){
                $result = ['status'=>'hasDependency'];
            }else{
                $miscellany->deleteCategory($idCategory);
                $result = ['status'=>'success'];
            }
            echo json_encode($result);
        }
    }

    private function categoryhasDependency(int $idCategory):bool {
        $miscellany = new Miscellany();
        $result = $miscellany->countProductsWithCategory($idCategory);
        if($result->count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function itbis(){
        $reques = $this->easy_request();

        switch ($reques->server->get('REQUEST_METHOD')){
            case 'GET':
                $idUser = $reques->query->filter('id_user');
                $miscellany = new Miscellany();
                echo json_encode($miscellany->getItbis($idUser));
                break;
            case 'POST':
                $miscellany = new Miscellany();
                $miscellany->updateItbis($reques->request->filter('id_user'),
                    [
                        'quantity'=>$reques->request->filter('quantity')
                    ]
                );
                echo json_encode(['status'=>'success']);
                break;
        }
    }

}