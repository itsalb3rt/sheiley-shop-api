<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 2/6/2019
 * Time: 4:57 PM
 */

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

                new RestResponse($users->getById($userCredentials->id_user), 200);
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

            $token = new Tokenista('sheiley');
            $user_data = [
                'first_name' => $newUser->first_name,
                'last_name' => $newUser->last_name,
                'user_name' => strtolower($newUser->user_name),
                'password' => $this->encrypt_password($newUser->password),
                'email' => $newUser->email,
                'token' => $token->generate()
            ];

            $users = new Users();

            if ($users->isExitsUserName($newUser->user_name)->count > 0) {
                new RestResponse([], 409, 'user exists', ['user exists']);
                return;
            }

            if ($users->isExitsEmail($newUser->email)->count > 0) {
                new RestResponse([], 409, 'email exists', ['email exists']);
                return;
            }

            $idUser = $users->create($user_data);
            $miscellany = new Miscellany();
            $miscellany->createInitialItbis([
                'quantity' => 1,
                'id_user' => $idUser
            ]);

            $miscellany->addMeasurementUnits([
                'name' => 'UNIDAD',
                'id_user' => $idUser
            ]);

            $miscellany->addCategory([
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
        $request = Request::createFromGlobals();
        if ($request->server->get('REQUEST_METHOD') === 'POST') {
            $token = $request->request->filter('token');
            $tokenista = new Tokenista('sheileyshop', ["lifetime" => 7200]);
            $accountRecovery = new AccountRecovery();
            $accountData = $accountRecovery->getByToken($token);

            if ($tokenista->isValid($token) === true && $tokenista->isExpired($token) === false && !empty($accountData)) {
                $password = $request->request->filter('password');
                $confirmPassword = $request->request->filter('confirm_password');
                $user = new Users();

                if ($this->password_match($password, $confirmPassword)) {
                    $user->update($accountData->id_user, [
                        'password' => $this->encrypt_password($password)
                    ]);
                    $accountRecovery->removeAccountRecoveryInformation($accountData->id_user);
                    http_response_code(200);
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(409);
                    echo json_encode(['status' => 'error', 'message' => 'password not match']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'the token is not valid']);
            }
        }
    }

    public function recovery()
    {
        $request = Request::createFromGlobals();
        if ($request->server->get('REQUEST_METHOD') === 'POST') {
            $email = $request->request->filter('email');
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
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'email no exists']);
            }
        }
    }

    public function userAlreadyExists($userName)
    {
        $request = Request::createFromGlobals();
        if ($request->server->get('REQUEST_METHOD') == 'GET') {
            $user = new Users();
            if ($user->isExitsUserName($userName)->count > 0) {
                echo json_encode(['status' => 'true']);
            } else {
                echo json_encode(['status' => 'false']);
            }
        }
    }

    public function emailAlreadyExists()
    {
        $request = Request::createFromGlobals();

        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            $user = new Users();
            if ($user->isExitsEmail($request->request->filter('email'))->count > 0) {
                echo json_encode(['status' => 'true']);
            } else {
                echo json_encode(['status' => 'false']);
            }
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