<?php
    namespace Requests;

    use Models\User;

    class UserRequest {

        private $user;

        public function __construct() {
            $this->user = new User();
        }

        public function post() {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $inputs = ['name','login','password'];
            $credentials = _checkRawData_($_POST,$inputs);
            if(! $credentials['success']){
                return [
                    'data' => ['errors'=>$credentials['data']],
                    'status' => 400,
                ];
            }
            $userExists = $this->user->checkUserExists($_POST['login']);
            if($userExists['data']){
                return [
                    'data' => $userExists['success'] ? ['error'=>'User already exists'] : ['error'=>$userExists['data']],
                    'status' => $userExists['success'] ? 400 : 500,
                ];
            }else{
                $newUser = $this->user->createUser($_POST);
                return [
                    'data' => $newUser['success'] ? ['newUser'=>$newUser['data'][0]] : ['error'=>$newUser['data']],
                    'status' => $newUser['success'] ? 200 : 400,
                ];
            }
        }

        public function get($url) {
            var_dump($url[1]);
            if(count($url) != 2 || !is_numeric($url[1])){
                return [
                    'data' => ['error' => 'Accessed page does not exist'],
                    'status' => 404,
                ];
            }
            $valideToken = _validateJWTToken_($url[1]);
            if(!$valideToken['success']){
                return [
                    'data' => ['error'=>$valideToken['data']],
                    'status' => 400,
                ];
            }
            $user = $this->user->getUser('id',$url[1]);
            if(! $user['success']){
                return [
                    'data' => ['error'=>$user['data']],
                    'status' => 500,
                ];
            }else{
                return [
                    'data' => $user['data'] ? ['user'=>$user['data'][0]] : ['error'=>'User not found or does not exist','user'=>null],
                    'status' => $user['data'] ? 200 : 404
                ];
            }    
        }
    }
?>