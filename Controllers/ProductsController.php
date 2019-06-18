<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 5/6/2019
 * Time: 2:15 PM
 */
use Core\Util\ligne_sessions\SessionsController;
use Models\Products\Products;

class ProductsController extends Controller
{

    public function __construct()
    {
        $session = new SessionsController();
        if($session->get('id_user') == false){
            $this->redirect(['controller'=>'auth','action'=>'login']);
        }
    }

    public function list(){
        $this->render('list','Mi lista');
    }

    public function add(){
        $this->render('add','Nuevo producto');
    }

    public function products(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $products = new Products();
            echo json_encode($products->products());
        }
    }

    public function createProduct(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            var_dump($_POST);
        }
    }

}