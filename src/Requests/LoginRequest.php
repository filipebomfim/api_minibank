<?php 
    namespace Requests;

    use Models\Login;
    

    class LoginRequest{
        private $login;

        public function __construct()
        {
            $this->login = new Login();
        }

        public function post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $inputs = ['login','password'];
            $credentials = _checkRawData_($_POST,$inputs);
            if(! $credentials['success']){
                return [
                    'data' => ['errors'=>$credentials['data']],
                    'status' => 400,
                ];
            }
            $login = $this->login->login($_POST); 
            if(! $login['success']){
                return [
                    'data' => ['errors'=>$login['data']],
                    'status' => 500,
                ];
            }else{
                if($login['data']){
                    $login['data'][0]['token'] = $this->login->generateJWTToken($login['data'][0]);
                }
                return [
                    'data' => $login['data'] ? ['user'=>$login['data'][0]] : ['error'=>'User not found or does not exist','user'=>null],
                    'status' => $login['data'] ? 200 : 404
                ];
            }
        }
    }
?>