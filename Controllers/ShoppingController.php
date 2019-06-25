<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 22/6/2019
 * Time: 4:02 PM
 */
use Models\Shopping\Shopping;
use Models\Miscellany\Miscellany;

class ShoppingController extends Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin:http://localhost:8080');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    }

    public function shopping(){
        $request = $this->easy_request();
        switch ($request->server->get('REQUEST_METHOD')){
            case 'GET':
                break;
            case 'POST':
                $idPurchase = $this->createPurchase($request->request->filter('idUser'));
                $this->createPurchaseDetails($idPurchase,$request->request->filter('shoppingCar'));
                $this->createEstablishmentName($request->request->filter('nameEstablishment'),$idPurchase);
                echo json_encode([
                    'status'=>'success'
                ]);
                break;
        }
    }

    private function createPurchase($idUser):int {
        $shopping = new Shopping();
        $data = [
            'date'=>date('Y-m-d H:i:s'),
            'id_user'=>$idUser
        ];
        return $shopping->createPurchase($data);
    }

    private function createPurchaseDetails(int $idPurchase,string $details):void{
        $purchase = new Shopping();
        $products = json_decode($details);
        $data = [];
        foreach ($products as $product){
            $data = [
                'id_purchase'=>$idPurchase,
                'product_name'=>$product->name,
                'unit_price'=>$product->price,
                'quantity'=>$product->quantity,
                'apply_itbis'=>$product->itbis,
                'category'=>$product->categoryName,
                'measurement_unit'=>$product->measurementUnitName
            ];
            $purchase->createDetailPurchase($data);
        }
    }

    private function createEstablishmentName($name,$idPurchase){
        $miscellany = new Miscellany();
        $data = [
            'id_purchase'=>$idPurchase,
            'name'=>$name
        ];
        $miscellany->createEstablishmentName($data);
    }
}