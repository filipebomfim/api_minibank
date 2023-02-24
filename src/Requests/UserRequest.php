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
                    'success' => false,
                    'title' => 'Unable to create new user',
                    'data' => ['errors'=>$credentials['data']],
                    'status' => 400,
                ];
            }
            $userExists = $this->user->checkUserExists($_POST['login']);
            if($userExists['data']){
                return [
                    'success' => false,
                    'title' => $userExists['success'] ? 'User already exists' : 'SQL connection failed',
                    'data' => $userExists['success'] ? null : ['errors'=>$userExists['data']],
                    'status' => $userExists['success'] ? 400 : 500,
                ];
            }else{
                $newUser = $this->user->createUser($_POST);
                return [
                    'success' => $newUser['success'] ? true :false,
                    'title' => $newUser['success'] ? 'Successfully created new user' : 'SQL connection failed',
                    'data' => $newUser['data'],
                    'status' => $newUser['success'] ? 200 : 400,
                ];
            }
        }

        public function get($url) {
            if(count($url) != 2 || !is_numeric($url[1])){
                return [
                    'success' => false,
                    'title' => 'Accessed page does not exist',
                    'data' => null,
                    'status' => 404,
                ];
            }
            $valideToken = _validateJWTToken_($url[1]);
            if(!$valideToken['success']){
                return [
                    'success' => false,
                    'title' => 'Error processing request',
                    'data' => ['errors'=>$valideToken['data']],
                    'status' => 400,
                ];
            }
            $user = $this->user->getUser($url[1]);
            if(! $user['success']){
                return [
                    'success' => false,
                    'title' => 'SQL connection failed',
                    'data' => ['errors'=>$user['data']],
                    'status' => 500,
                ];
            }else{
                return [
                    'success' => $user['data'] ? true : false,
                    'title' => $user['data'] ? 'User Data' : 'User not found or does not exist',
                    'data' => $user['data'] ? ['authenticated'=>$user['data'][0]] : ['authenticated'=>null],
                    'status' => $user['data'] ? 200 : 404
                ];
            }
        }
    }
?>