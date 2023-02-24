<?php 
    namespace Models;

    use Models\Database;

    class Account {

        private $db;
        public function __construct(){
            $this->db = Database::getConnection();
        }

        public function create($userId, $accountNumber){
            try {
                $sql = $this->db->prepare(
            "INSERT INTO account (number, user_id) VALUES (?,?)"
                );
                $sql->execute(array($userId, $accountNumber));
                
                return [
                    'success'=>true,
                    'data'=>'Account '.$accountNumber.' created!'
                ];
            } catch (\Throwable $th) {
                return [
                    'success'=>false,
                    'data'=>$th->getMessage()
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