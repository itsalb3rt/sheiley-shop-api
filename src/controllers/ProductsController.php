<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 5/6/2019
 * Time: 2:15 PM
 */

use App\models\Products\Products;
use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends Controller
{

    private $request;
    private $userToken;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        new SecureApi($this->request);
    }

    public function products($id = null)
    {
        $user = new Users();
        $user = $this->request->get('user');
        switch ($this->request->server->get('REQUEST_METHOD')) {
            case 'GET':

                if ($id === null) {
                    $products = new Products();
                    new RestResponse($products->getAll($user->id_user));
                    return;
                }

                $products = new Products();

                if ($this->request->query->get('include_purchases')) {
                    $productResult = $products->getById($user->id_user, $id);
                    $productResult->{"purchases"} = $products->getPurchases($id);
                    new RestResponse($productResult);
                    return;
                }

                new RestResponse($products->getById($user->id_user, $id));
                break;
            case 'POST':
                $this->add();
                break;
            case 'PATCH':
                $this->update($id);
                break;
            case 'DELETE':
                $this->delete($id);
                break;
        }
    }

    /**
     * Return all brands of products by logged user
     */
    public function brands(){
        $user = new Users();
        $user = $this->request->get('user');
        if ($this->request->server->get('REQUEST_METHOD') === 'GET') {
            $products = new Products();
            $brands = $products->getAllBrands($user->id_user);
            $result = [];
            foreach ($brands as $key => $value){
                $result[] = $value->brand;
            }
            new RestResponse($result);
            return;
        }
    }

    public function update($idProduct)
    {
        $products = new Products();
        $product = json_decode($this->request->getContent(), true);
        $user = new Users();
        $user = $this->request->get('user');

        $products->update($idProduct, $product);
        new RestResponse($products->getById($user->id_user,$idProduct), 200, 'product updated');
    }

    private function add()
    {
        $products = new Products();
        $product = json_decode($this->request->getContent(), true);
        $user = new Users();
        $user = $this->request->get('user');
        $result = null;

        if ($this->productReadyExitst($product['name'],$product['brand'], $user->id_user)) {
            $result = [
                'product name exits'
            ];
            new RestResponse($result, 409, '', $result);
            return;
        }
        $product['id_user'] = $user->id_user;
        $insertId = $products->add($product);
        $product = $products->getById($user->id_user,$insertId);
        new RestResponse($product, 201,'product created');
        return;

    }

    public function delete($idProduct): void
    {
        $product = new Products();
        $user = new Users();
        $user = $this->request->get('user');

        $product->delete($idProduct,$user->id_user);
        new RestResponse(null, 200, 'product deleted');
    }

    private function productReadyExitst($name,$brand, $idUser): bool
    {
        $product = new Products();
        $product = $product->getProductByNameAndBrand(strtoupper($name),strtoupper($brand), $idUser);
        if ($product->count > 0) {
            return true;
        } else {
            return false;
        }
    }

}