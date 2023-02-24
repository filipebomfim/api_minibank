<?php 

    namespace Models;
    
    class Database {
        private static $pdo;
        private function __construct(){}
        
        /*
            Função estática para realizar a conexão com o banco de dados
        */
        public static function getConnection(){
            if(!isset(self::$pdo)){
                try {
                    self::$pdo=new \PDO('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD,array(\PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
                    self::$pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    
                } catch (\Exception $e) {
                    echo    '<div class="alert alert-danger" role="alert">
                                Erro :'.$e.'
                            </div>';
                }
            }

            return self::$pdo;
        }

    }
?>