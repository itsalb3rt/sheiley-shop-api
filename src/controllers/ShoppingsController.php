<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 22/6/2019
 * Time: 4:02 PM
 */

use App\models\Shoppings\Shoppings;
use App\models\Miscellany\Miscellany;
use App\models\Taxes\Taxes;
use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class ShoppingsController extends Controller
{

    private $request;
    private $userToken;

    public function __construct()
    {
        new SecureApi();
        $this->request = Request::createFromGlobals();
        $this->userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
    }


    public function shoppings($id = null)
    {
        $user = new Users();
        $user = $user->getByToken($this->userToken);
        $shopping = new Shoppings();

        switch ($this->request->server->get('REQUEST_METHOD')) {
            case 'GET':
                if ($id === null) {
                    $history = $this->getAll($user->id_user);
                    new RestResponse($history,200);
                } else {
                    $details = $this->getById($user->id_user,$id);
                    new RestResponse($details);
                }
                break;
            case 'POST':
                $idPurchase = $this->createPurchase($user->id_user);
                $newShopping = json_decode($this->request->getContent());
                $this->createPurchaseDetails($idPurchase, $newShopping->shoppingCar);
                $this->createEstablishmentName($newShopping->nameEstablishment, $idPurchase);
                new RestResponse($shopping->purchase($idPurchase), 201, 'created');
                break;
            case 'DELETE':
                $miscellany = new Miscellany();
                $shopping->deletePurchase($id,$user->id_user);
                $shopping->deletePurchaseDetails($id);
                $miscellany->deleteEstablishmentName($id);
                new RestResponse(null,200,'deleted');
                break;
            default:
                new RestResponse(null, 405, "Method Not Allowed");
                break;
        }
    }

    private function getAll($idUser){
        $shopping = new Shoppings();
        return $shopping->getAll($idUser);
    }

    private function getById($idUser, $idPurchase)
    {
        $miscellany = new Miscellany();
        $taxes = new Taxes();
        $shopping = new Shoppings();
        $shoppingResult = $shopping->purchase($idPurchase);

        if(!empty($shopping->purchase($idPurchase))){
            $details = [
                'purchase_details' => $shopping->purchaseDetails($idPurchase),
                'purchase' => $shoppingResult,
                'establishment' => $miscellany->namesEstablishments($idPurchase),
                'tax' => $taxes->get($idUser)
            ];
            return $details;
        }
        return [];
    }

    private function createPurchase($idUser): int
    {
        $shopping = new Shoppings();
        $data = [
            'create_at' => date('Y-m-d H:i:s'),
            'id_user' => $idUser
        ];
        return $shopping->createPurchase($data);
    }

    private function createPurchaseDetails(int $idPurchase, array $details): void
    {
        $purchase = new Shoppings();
        $products = $details;
        $data = [];
        foreach ($products as $product) {
            $data = [
                'id_purchase' => $idPurchase,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $product->quantity,
                'include_tax' => $product->include_tax,
                'category' => $product->categoryName,
                'measurement_unit' => $product->measurementUnitName,
                'brand'=>$product->brand,
                'product_id'=>$product->id_product
            ];
            $purchase->createDetailPurchase($data);
        }
    }

    private function createEstablishmentName($name, $idPurchase)
    {
        $miscellany = new Miscellany();
        $data = [
            'id_purchase' => $idPurchase,
            'name' => $name
        ];
        $miscellany->createEstablishmentName($data);
    }
}