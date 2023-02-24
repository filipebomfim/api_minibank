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
    }
?>