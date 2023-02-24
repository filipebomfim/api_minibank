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
            $credentials = $this->login->checkLoginCredentials($_POST);
            if($credentials['success'] == false){
                return [
                    'success' => false,
                    'title' => 'Unable to login',
                    'data' => ['errors'=>$credentials['data']],
                    'status' => 400,
                ];
            }
            $login = $this->login->login($_POST); 
            if(!$login['success']){
                return [
                    'success' => false,
                    'title' => 'SQL connection failed',
                    'data' => ['errors'=>$login['data']],
                    'status' => 400,
                ];
            }else{
                if($login['data']){
                    $login['data'][0]['token'] = $this->login->generateJWTToken($login['data'][0]);
                }
                return [
                    'success' => true,
                    'title' => $login['data'] ? 'Login successful' : 'User not found or does not exist',
                    'data' => $login['data'] ? ['authenticated'=>$login['data'][0]] : ['authenticated'=>null],
                    'status' => $login['data'] ? 200 : 400
                ];
            }
        }
    }
?>