<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 2/6/2019
 * Time: 4:57 PM
 */

use App\models\Categories\Categories;
use App\models\MeasurementUnits\MeasurementUnits;
use App\models\Users\Users;
use App\models\Miscellany\Miscellany;
use App\plugins\RestResponse;
use Symfony\Component\HttpFoundation\Request;
use Ingenerator\Tokenista;
use App\plugins\AccountRecoveryEmailSend;
use App\models\AccountRecovery\AccountRecovery;
use App\plugins\SecureApi;

class AuthController extends Controller
{

    private $request;

    public function __construct()
    {
        new SecureApi(true);
        $this->request = Request::createFromGlobals();
    }


    public function login()
    {
        $request = Request::createFromGlobals();

        if ($request->getMethod() == 'POST') {
            $requestUser = json_decode($this->request->getContent());
            $users = new Users();

            $userCredentials = $users->getByUserName(strtolower($requestUser->user_name));

            if (empty($userCredentials) === false
                && password_verify($requestUser->password, $userCredentials->password)) {
                $token = new Tokenista('sheiley');
                $users->update($userCredentials->id_user, ['token' => $token->generate()]);
                $users = $users->getById($userCredentials->id_user);
                unset($users->password);
                new RestResponse($users, 200, 'user logged');
                return;
            } else {
                new RestResponse([], 401, 'wrong credentials', ['wrong credentials']);
            }
        }
        return;
    }

    public function register()
    {
        $newUser = json_decode($this->request->getContent());
        if ($this->request->server->get('REQUEST_METHOD') == 'POST') {

            if (!$this->password_match($newUser->password, $newUser->password2)) {
                new RestResponse([], 409, '', ['password not match']);
                return;
            }

            $firstname = " ";
            $lastname = " ";
            $username = strtolower(strstr($newUser->email, '@', true));

            $parts = explode(" ", $newUser->fullname);

            if(count($parts) > 1) {
                $lastname = array_pop($parts);
                $firstname = implode(" ", $parts);
            }else{
                $firstname = $name;
                $lastname = " ";
            }

            $token = new Tokenista('sheiley');
            $user_data = [
                'first_name' => $firstname,
                'last_name' => $lastname,
                'user_name' => $username,
                'password' => $this->encrypt_password($newUser->password),
                'email' => strtolower($newUser->email),
                'token' => $token->generate()
            ];

            $users = new Users();

            if ($users->isExitsUserName($username)->count > 0) {
                new RestResponse([], 409, 'user exists', ['user exists']);
                return;
            }

            if ($users->isExitsEmail($newUser->email)->count > 0) {
                new RestResponse([], 409, 'email exists', ['email exists']);
                return;
            }

            $idUser = $users->create($user_data);
            $miscellany = new Miscellany();
            $miscellany->createInitialTaxes([
                'quantity' => 1,
                'id_user' => $idUser
            ]);

            $measurementsUnits = new MeasurementUnits();
            $measurementsUnits->create([
                'name' => 'UNIDAD',
                'id_user' => $idUser
            ]);

            $categories = new Categories();
            $categories->create([
                'name' => 'SIN CATEGORIA',
                'id_user' => $idUser
            ]);
            $newUser = $users->getById($idUser);
            unset($newUser->password);
            new RestResponse($newUser, 201);
            return;
        }
    }

    public function resetPassword()
    {
        if ($this->request->server->get('REQUEST_METHOD') === 'POST') {
            $data = json_decode($this->request->getContent());

            if(!isset($data->token) || !isset($data->password) || !isset($data->password_confirm)){
                new RestResponse([],409,'Invalid json object');
                return;
            }

            $tokenista = new Tokenista('sheileyshop', ["lifetime" => 7200]);
            $accountRecovery = new AccountRecovery();
            $accountData = $accountRecovery->getByToken($data->token);

            if ($tokenista->isValid($data->token) === true && $tokenista->isExpired($data->token) === false && !empty($accountData)) {
                $password = $data->password;
                $confirmPassword = $data->password_confirm;
                $user = new Users();

                if ($this->password_match($password, $confirmPassword)) {
                    $user->update($accountData->id_user, [
                        'password' => $this->encrypt_password($password)
                    ]);
                    $accountRecovery->removeAccountRecoveryInformation($accountData->id_user);
                    new RestResponse([],200,'password change');
                    return;
                } else {
                    new RestResponse([],409,'password not match');
                    return;
                }
            } else {
                new RestResponse([],409,'Invalid token');
                return;
            }
        }
    }

    public function recovery()
    {
        if ($this->request->server->get('REQUEST_METHOD') === 'POST') {
            $recoveryAccount = json_decode($this->request->getContent());

            if(!isset($recoveryAccount->email)){
                new RestResponse([],409,'email not found',['email not found']);
                return;
            }

            $email = $recoveryAccount->email;
            $user = new Users();

            if ($user->isExitsEmail($email)->count > 0) {
                $token = 'sheileyshop';
                $tokenista = new Tokenista($token, ["lifetime" => 7200]);
                $token = $tokenista->generate();
                $userData = $user->getByEmail($email);
                $accountRecovery = new AccountRecovery();
                $accountRecovery->removeAccountRecoveryInformation($userData->id_user);
                $accountRecovery->setAccountRecoveryInformation([
                    'id_user' => $userData->id_user,
                    "single_use_token" => $token
                ]);
                $emailSend = new AccountRecoveryEmailSend($email, $token);
                $emailSend->send();
                new RestResponse([],200,'email send');
                return;
            } else {
                new RestResponse([],409,'email no register',['email no register']);
                return;
            }
        }
    }

    /**
     * Using for check an user name exists
     * @param null $userName
     */
    public function users($userName = null){
        if ($this->request->server->get('REQUEST_METHOD') === 'GET' && $userName !== null) {
            $users = new Users();
            $user = $users->getByUserName($userName);
            if(empty($user)){
                new RestResponse([],404,'user not found');
                return;
            }

            new RestResponse([],200,'user exists');
            return;
        }
    }

    /**
     * Use for check if an email exits
     */
    public function emails(){
        if ($this->request->server->get('REQUEST_METHOD') === 'POST') {
            $users = new Users();
            $data = json_decode($this->request->getContent());

            if(!isset($data->email)){
                new RestResponse([],409,'email key no exists');
                return;
            }

            $data = $users->getByEmail($data->email);
            if(empty($data)){
                new RestResponse([],404,'email not found');
                return;
            }

            new RestResponse([],200,'email exists');
            return;
        }
    }

    //Utils Private

    private function password_match($password1, $password2)
    {
        if ($password1 == $password2)
            return true;
        else
            return false;
    }

    private function encrypt_password($password)
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}