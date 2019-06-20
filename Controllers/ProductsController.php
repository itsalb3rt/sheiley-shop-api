<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 5/6/2019
 * Time: 2:15 PM
 */
use Models\Products\Products;
class ProductsController extends Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin:http://localhost:8080');
    }

    public function list($idUser){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $products = new Products();
            echo json_encode($products->products($idUser));
        }
    }

    public function add(){
        $request = $this->easy_request();
        if($request->server->get('REQUEST_METHOD') == 'POST'){
            $newProduct = new Products();
            //Front object
            $product = json_decode($request->request->get('product'),true);
            $result = null;
            if($this->productReadyExitst($product['name'],$product['id_user'])){
                $result = [
                    'status'=>'exits',
                    'data'=>[
                        'name'=>$product['name']
                    ]
                ];
            }else{
                $insertId = $newProduct->add($product);
                $result = [
                    'status'=>'success',
                    'data'=>[
                        'id_product'=>$insertId
                    ]
                ];

            }
            echo json_encode($result);
        }
    }

    private function productReadyExitst($name,$idUser):bool {
        $product = new Products();
        $product = $product->getProductByName(strtoupper($name),$idUser);
        if($product->count > 0){
            return true;
        }else{
            return false;
        }
    }

}