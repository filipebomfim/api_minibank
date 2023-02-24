<?php 

    namespace Router;

    class Router
    {
        public $routes = array();

        public function __construct()
        {
          //Endpoints para Login
          $this->addNewRoute("login", "post");

          //Endpoints para Usuário
          $this->addNewRoute("user", "post");
          $this->addNewRoute("user", "get");

          //Endpoints para Depósito
          $this->addNewRoute("deposit", "get");
          
      
          $this->getRequestRoute();
        }

        private function addNewRoute($route, $method)
        {
          $route = array(
            "route" => $route, 
            "method" => $method
          );
          array_push($this->routes, $route);
        }
      
        private function getRequestRoute()
        {
          if(!isset($_REQUEST['url'])){
            _response_(
              false,
              'A página acessada não existe',
              'Sem dados',
              404
            );
          } 

          $routeURL = explode("/", $_REQUEST['url']);
          $method = strtolower($_SERVER['REQUEST_METHOD']);
          $request = "\Requests\\".ucfirst($routeURL[0]).'Request'; 

          foreach ($this->routes as $key => $route) {
            if($routeURL[0] == $route['route'] && $method == $route['method']){
              $response = @call_user_func(array(new $request, $method),$routeURL); 
              if($response){
                _response_(
                  $response['success'],
                  $response['title'],
                  $response['data'],
                  $response['status']
                );
              }     
            }
          }   
          
          _response_(
            false,
            'A página acessada não existe',
            'Sem dados',
            404
          );
        }
    }
?>