<?php 
    namespace Models;

    use Models\Database;

    class User {

        private $db;

        public function __construct() {
            $this->db = Database::getConnection();
        }

        public function createUser($request){
            try {
                $sql = $this->db->prepare(
                    "INSERT INTO users (name, login, password) VALUES (?,?,?)"
                );
                $sql->execute(array($request['name'], $request['login'], $request['password']));
                $newUser = $this->getUser('login',$request['login']);
                return [
                    'success'=>true,
                    'data'=>$newUser['data']
                ];
            } catch (\Throwable $th) {
                return [
                    'success'=>false,
                    'data'=>$th->getMessage()
                ];
            }
        }

        public function getUser($param, $value){
            try {
                $sql = $this->db->prepare(
                    "SELECT * FROM users WHERE $param = ?"
                );
                $sql->execute(array($value));
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

        public function checkUserExists($login){
            try {
                $sql = $this->db->prepare(
                    "SELECT * FROM users WHERE login = ? "
                );
                $sql->execute(array($login));
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
    }
?>