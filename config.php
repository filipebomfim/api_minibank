<?php
    //Set do zona para data e hora do servidor
    date_default_timezone_set('America/Sao_Paulo');

    //Dados de acesso ao banco de dados
    define('HOST','localhost');
    define('USER','root');
    define('PASSWORD','');
    define('DATABASE','minibank');

    //Função para retorno de resposta em JSON
    function _response_( $success = true, $title = null, $data = array(), $response_cod = 200 )
    {
        header('Content-Type: application/json; charset=utf-8');
        //Configuração do Código de resposta HTTP
        http_response_code($response_cod);
        echo json_encode(
            array(
                'success' => $success,
                'title' => $title,
                'data'   => $data
            )
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

?>