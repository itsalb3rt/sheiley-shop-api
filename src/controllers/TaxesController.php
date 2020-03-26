<?php


use App\models\Taxes\Taxes;
use App\models\Users\Users;
use App\plugins\RestResponse;
use App\plugins\SecureApi;
use Symfony\Component\HttpFoundation\Request;

class TaxesController extends Controller
{
    private $request;
    private $userToken;

    public function __construct()
    {
        new SecureApi();
        $this->request = Request::createFromGlobals();
        $this->userToken = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
    }

    public function taxes()
    {
        $user = new Users();
        $user = $user->getByToken($this->userToken);
        switch ($this->request->server->get('REQUEST_METHOD')) {
            case 'GET':
                $taxes = new Taxes();
                new RestResponse($taxes->get($user->id_user));
                return;
                break;
            case 'PATCH':
                $updateTax = json_decode($this->request->getContent(), true);
                $taxes = new Taxes();
                $taxes->update($user->id_user,$updateTax);
                new RestResponse($taxes->get($user->id_user),200,'updated');
                break;
            case 'POST':
            case 'DELETE':
                new RestResponse(null, 405, "Method Not Allowed");
                break;
        }
    }

}