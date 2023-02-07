<?php
    //Set do zona para data e hora do servidor
    date_default_timezone_set('America/Sao_Paulo');

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
?>