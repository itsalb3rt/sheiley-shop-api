<?php

use App\models\MeasurementUnits\MeasurementUnits;
use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class MeasurementunitsController extends Controller
{
    private $request;
    private $userToken;

    public function __construct()
    {
        new SecureApi();
        $this->request = Request::createFromGlobals();
        $this->userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
    }

    public function measurementunits($id = null){
        $user = new Users();
        $user = $user->getByToken($this->userToken);
        switch ($this->request->server->get('REQUEST_METHOD')) {
            case 'GET':
                $measurementunits = new MeasurementUnits();
                if ($id === null) {
                    new RestResponse($measurementunits->getAll($user->id_user));
                    return;
                }
                new RestResponse($measurementunits->getById($user->id_user, $id));
                break;
            case 'POST':
                $this->add();
                break;
            case 'PATCH':
                new RestResponse(null,405,"Method Not Allowed");
                break;
            case 'DELETE':
                $this->delete();
                break;
        }
    }

    private function get($id_user){
        $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $miscellany = new Miscellany();
            echo json_encode($miscellany->measurementUnits($id_user));
        }
    }

    private function add(){

        if($this->request->server->get('REQUEST_METHOD') == 'POST'){
            $user = new Users();
            $user = $user->getByToken($this->userToken);

            $newMeasurementUnit = json_decode($this->request->getContent(), true);
            $measurementUnits = new MeasurementUnits();

            $insertId = $measurementUnits->create([
                'name'=>$newMeasurementUnit['name'],
                'id_user'=>$user->id_user,
            ]);
            new RestResponse($measurementUnits->getById($insertId,$user->id_user),201,'measurement unit created');
            return;
        }
    }


    private function delete($idUnitMeasurement = null){
        $request = Request::createFromGlobals();
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
}