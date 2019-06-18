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
    public function measurementUnits(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->measurementUnits());
        }
    }

    public function categories(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->categories());
        }
    }

    public function addMeasurementUnit(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'POST'){
            var_dump($_POST);
        }
    }

    public function measurement_units_view(){
        $this->render('measurement_units','Unidades Medidas');
    }
}