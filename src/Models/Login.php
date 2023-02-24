<?php 
    namespace Models;

    use Models\Database;

    class Login {

        private $db;
        public function __construct(){
            $this->db = Database::getConnection();
        }

        public function login($credentials){
            try {
                $sql = $this->db->prepare(
                    "SELECT * FROM users WHERE login = ? AND password = ?"
                );
                $sql->execute(array($credentials['login'], $credentials['password']));
                $data = $sql->fetchAll(\PDO::FETCH_ASSOC);
                return [
                    'success'=>true,
                    'data'=>$data
                ];
            } catch (\Throwable $th) {
                return [
                    'success'=>false,
                    'data'=>$th->getMessage()
                ];
            }
        }

        public function checkLoginCredentials($request){
            $inputs = ['login','password'];
            $errors = [];
            foreach($inputs as $input){
                if(!(isset($request[$input])) || $request[$input]==null) {
                    array_push($errors,$input.' field must be filled'); 
                }            
            }

            if ($errors) {
                return [
                    'success' => false,
                    'data' => $errors
                ];
            }else{
                return [
                    'success' => true,
                ];
            }
        }

        public function generateJWTToken($login){
            $keyJWT = 'api_minibank';
            //Header do Token JWT
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];
            //Conteúdo do Payload
            //Validade do Token de 30 minutos e armazenamento do login do usuário para indicar a quem pertence o Token
            $payload = [
                'iat' =>  time(),
                'exp' => time()+(60*30),
                'uid' => 1,
                'id' => $login['id'],
                'login' => $login['login'],
            ];
            //JSON
            $header = json_encode($header);
            $payload = json_encode($payload);
            //Codificação Base 64
            $header = base64_encode($header);
            $payload = base64_encode($payload);
            //Assinatura
            $sign = hash_hmac('sha256', $header . "." . $payload, $keyJWT, true);
            $sign = base64_encode($sign);
            //Token
            $token = $header . '.' . $payload . '.' . $sign;
            return $token;
        }

    }
?>