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
        if(ENVIROMENT == 'dev'){
            $origin = "http://localhost:8080";
        }else{
            $origin = "https://gibucket.a2hosted.com";
        }

        header("Access-Control-Allow-Origin:$origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    }


    public function shopping(){
        $request = $this->easy_request();
        switch ($request->server->get('REQUEST_METHOD')){
            case 'GET':
                $shopping = new Shopping();
                $miscellany = new Miscellany();

                $idPurchase = $request->query->filter('id_purchase');
                $purchase_details = $shopping->purchaseDetails($idPurchase);
                $purchase = $shopping->purchase($idPurchase);
                $namesEstablishment = $miscellany->namesEstablishments($idPurchase);
                $itbis_quantity = $miscellany->getItbis($purchase->id_user);
                echo json_encode([
                    'status'=>'success',
                    'purchase_details'=>$purchase_details,
                    'purchase'=>$purchase,
                    'establishment'=>$namesEstablishment,
                    'itbis_quantity'=>$itbis_quantity
                ]);
                break;
            case 'POST':
                $idPurchase = $this->createPurchase($request->request->filter('idUser'));
                $this->createPurchaseDetails($idPurchase,$request->request->filter('shoppingCar'));
                $this->createEstablishmentName($request->request->filter('nameEstablishment'),$idPurchase);
                echo json_encode([
                    'status'=>'success'
                ]);
                break;
            case 'DELETE':
                $idPurchase = $request->query->filter('id_purchase');
                $shopping = new Shopping();
                $miscellany = new Miscellany();
                $shopping->deletePurchase($idPurchase);
                $shopping->deletePurchaseDetails($idPurchase);
                $miscellany->deleteEstablishmentName($idPurchase);
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