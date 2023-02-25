<?php
    //Set do zona para data e hora do servidor
    date_default_timezone_set('America/Sao_Paulo');

    //Dados de acesso ao banco de dados
    define('HOST','localhost');
    define('USER','root');
    define('PASSWORD','');
    define('DATABASE','minibank');

    //Chave de Acesso para o Token JWT
    define('KEY_JWT','api_minibank');

    //Função para retorno de resposta em JSON
    function _response_( $data = array(), $response_cod = 200 )
    {
        header('Content-Type: application/json; charset=utf-8');
        //Configuração do Código de resposta HTTP
        http_response_code($response_cod);
        echo json_encode(   
            $data
        );
        exit;
    }

    function _checkRawData_($request,$inputs){
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

    function _validateJWTToken_($userID = null){
        //Pega os headers da requisição HTTP e verifica se o Token JWT não foi enviado nela               
        $http_header = apache_request_headers();
        if(!isset($http_header['Authorization']) || !$http_header['Authorization']){
            return[
                'success'=>false,
                'data'=>'Missing authorization token'
            ]; 
        }
        //Tratamento do Token JWT para validação
        $bearer = explode (' ', $http_header['Authorization']);
        $token = explode('.', $bearer[1]);
        $header = $token[0];
        $payload = $token[1];
        $payload_data_token = json_decode(base64_decode($payload),true);
        $payload_data_token['exp'] = date('Y-m-d H:i:s',$payload_data_token['exp']);
        $sign = $token[2];

        //Verifica se a assinatura do Token JWT não é válida
        $valid = hash_hmac('sha256', $header . "." . $payload, KEY_JWT, true);
        $valid = base64_encode($valid);
        if(!($sign === $valid)){
            return[
                'success'=>false,
                'data'=>'Invalid user token'
            ]; 
        }
        //Verifica se o Token JWT está expirado
        if(date('Y-m-d H:i:s') > $payload_data_token['exp']){
            return[
                'success'=>false,
                'data'=>'Expired user token'
            ]; 
        }
        //Verifica se o Token JWT pertence ao usuário da requisição
        if($userID){
            if($userID != $payload_data_token['id']){
                return[
                    'success'=>false,
                    'data'=>'Authenticated user token does not allow performing actions for the selected user.'
                ]; 
            }
        }
        //Token válido
        return [
            'success'=>true,
            'data'=>'Valid user token'
        ];
        
    }

?>