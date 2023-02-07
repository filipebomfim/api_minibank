<?php 
    namespace Requests;

    class DepositRequest{
        private $deposit;

        public function __construct()
        {
            
        }

        public function get($param){
            return [
                'success'=>true,
                'title'=>'SUCESSO NO GET',
                'data'=> 'DADOS',
                'status'=>200
            ];  
        }
    }
?>