<?php

use App\models\Categories\Categories;
use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends Controller
{
    private $request;
    private $userToken;

    public function __construct()
    {
        new SecureApi();
        $this->request = Request::createFromGlobals();
        $this->userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
    }

    public function categories($id = null){
        $user = new Users();
        $user = $user->getByToken($this->userToken);
        switch ($this->request->server->get('REQUEST_METHOD')) {
            case 'GET':
                $categories = new Categories();
                if ($id === null) {
                    new RestResponse($categories->getAll($user->id_user));
                    return;
                }
                new RestResponse($categories->getById($user->id_user, $id));
                break;
            case 'POST':
                $this->add();
                break;
            case 'PATCH':
                new RestResponse(null,405,"Method Not Allowed");
                break;
            case 'DELETE':
                $this->delete($id);
                break;
        }
    }

    private function add(){
        $user = new Users();
        $user = $user->getByToken($this->userToken);

        if($this->request->server->get('REQUEST_METHOD') == 'POST'){
            $newCategory = json_decode($this->request->getContent(), true);
            $categories = new Categories();
            $insertId = $categories->create([
                'name'=>$newCategory['name'],
                'id_user'=>$user->id_user,
            ]);

            new RestResponse($categories->getById($insertId,$user->id_user),201,'category created');
            return;
        }
    }

    private function delete($idCategory = null) {
        if($this->request->server->get('REQUEST_METHOD') == 'GET' && $idCategory != null){
            $categories = new Categories();
            $result = null;
            if($this->categoryhasDependency($idCategory)){
                new RestResponse(null,409,'this category has products dependencies');
                return;
            }

            $categories->delete($idCategory);
            new RestResponse(null,200,'deleted');
            return;
        }
    }


    private function categoryhasDependency(int $idCategory):bool {
        $categories = new Categories();
        $result = $categories->countProductsWithCategory($idCategory);
        if($result->count > 0){
            return true;
        }else{
            return false;
        }
    }
}