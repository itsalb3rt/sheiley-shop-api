<?php
/**
 * Created by PhpStorm.
 * User: destroid
 * Date: 2/6/2019
 * Time: 4:57 PM
 */
use App\models\User\User;
use App\models\Miscellany\Miscellany;
use Ligne\SessionsController;
use Symfony\Component\HttpFoundation\Request;
use Ingenerator\Tokenista;
use App\plugins\AccountRecoveryEmailSend;
use App\models\AccountRecovery\AccountRecovery;

class AuthController extends Controller
{
    public function __construct()
    {
        if(ENVIROMENT == 'dev'){
            $origin = "http://localhost:8080";
        }else{
            $origin = "https://gibucket.a2hosted.com";
        }
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Access-Control-Allow-Origin:$origin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    }


    public function login_check(){
         $request = Request::createFromGlobals();

        if($request->getMethod() == 'POST'){
            $new_login = new User();
            $user_credentials = $new_login->getUser( strtolower( $request->request->filter('user_name') ) );

            if( empty($user_credentials) === false
                && password_verify($request->request->filter('password'),$user_credentials->password)){

                $this->createUserSession($user_credentials);
                echo json_encode(['status'=>'login_correct','user'=>$user_credentials]);
            }else{
                echo json_encode(['status'=>'login_failed']);
            }
        }
    }

    public function logout(){
        $session = new SessionsController();
        $session->destroy_all_session();
    }

    public function register_user(){
         $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') == 'POST'){

            if(!$this->password_match($request->request->get('password'),$request->request->get('password2'))){
                echo json_encode(['status'=>'bad_password']);
                exit();
            }

            $user_data = [
                'first_name'=>$request->request->filter('first_name'),
                'last_name'=>$request->request->filter('last_name'),
                'user_name'=>strtolower($request->request->filter('user_name')),
                'password'=>$this->encrypt_password($request->request->filter('password')),
                'email'=>$request->request->filter('email'),
            ];

            $new_user = new User();

            if( $new_user->isExitsUserName($user_data['user_name'])->count > 0){
                echo json_encode(['status'=>'user_exists']);
                exit();
            }

            if( $new_user->isExitsEmail($user_data['email'])->count > 0){
                echo json_encode(['status'=>'email_exists']);
                exit();
            }

            $idUser = $new_user->registerUser($user_data);
            $miscellany = new Miscellany();
            $miscellany->createInitialItbis([
                'quantity'=>1,
                'id_user'=>$idUser
            ]);

            $miscellany->addMeasurementUnits([
                'name' => 'UNIDAD',
                'id_user' => $idUser
            ]);

            $miscellany->addCategory([
               'name'=>'SIN CATEGORIA',
                'id_user'=>$idUser
            ]);

            echo json_encode(['status'=>'register']);
        }
    }

    public function resetPassword(){
        $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') === 'POST'){
            $token = $request->request->filter('token');
            $tokenista = new Tokenista('sheileyshop',["lifetime"=>7200]);
            $accountRecovery = new AccountRecovery();
            $accountData = $accountRecovery->getByToken($token);

            if($tokenista->isValid($token) === true && $tokenista->isExpired($token) === false && !empty($accountData)){
                $password = $request->request->filter('password');
                $confirmPassword = $request->request->filter('confirm_password');
                $user = new User();

                if($this->password_match($password,$confirmPassword)){
                    $user->updateUser($accountData->id_user,[
                        'password'=>$this->encrypt_password($password)
                    ]);
                    $accountRecovery->removeAccountRecoveryInformation($accountData->id_user);
                    http_response_code(200);
                    echo json_encode(['status'=>'success']);
                }else{
                    http_response_code(409);
                    echo json_encode(['status'=>'error','message'=>'password not match']);
                }
            }else{
                http_response_code(401);
                echo json_encode(['status'=>'error','message'=>'the token is not valid']);
            }
        }
    }

    public function recovery(){
        $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') === 'POST'){
            $email = $request->request->filter('email');
            $user = new User();

            if($user->isExitsEmail($email)->count > 0){
                $token = 'sheileyshop';
                $tokenista = new Tokenista($token,["lifetime"=>7200]);
                $token = $tokenista->generate();
                $userData = $user->getByEmail($email);
                $accountRecovery = new AccountRecovery();
                $accountRecovery->removeAccountRecoveryInformation($userData->id_user);
                $accountRecovery->setAccountRecoveryInformation([
                    'id_user'=>$userData->id_user,
                    "single_use_token"=>$token
                ]);
                $emailSend = new AccountRecoveryEmailSend($email,$token);
                $emailSend->send();
                echo json_encode(['status'=>'success']);
            }else{
                echo json_encode(['status'=>'error','message'=>'email no exists']);
            }
        }
    }

    public function userAlreadyExists($userName){
         $request = Request::createFromGlobals();
        if($request->server->get('REQUEST_METHOD') == 'GET'){
            $user = new User();
            if($user->isExitsUserName($userName)->count > 0){
                echo json_encode(['status'=>'true']);
            }else{
                echo json_encode(['status'=>'false']);
            }
        }
    }
    public function emailAlreadyExists(){
         $request = Request::createFromGlobals();

        if($request->server->get('REQUEST_METHOD') == 'POST'){
            $user = new User();
            if($user->isExitsEmail($request->request->filter('email'))->count > 0){
                echo json_encode(['status'=>'true']);
            }else{
                echo json_encode(['status'=>'false']);
            }
        }
    }

    //Utils Private

    private function createUserSession(object $user){
        $session = new SessionsController();
        $session->set('id_user',$user->id_user)
            ->set('user_name',$user->user_name)
            ->set('first_name',$user->first_name)
            ->set('last_name',$user->last_name)
            ->set('email',$user->email)
            ->set('is_login','true');
    }

    private function password_match($password1,$password2){
        if($password1 == $password2)
            return true;
        else
            return false;
    }

    private function encrypt_password($password){
        return password_hash($password,PASSWORD_ARGON2I);
    }
}