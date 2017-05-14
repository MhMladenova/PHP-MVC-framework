<?php
namespace MVCF;

class FrontController {
    
    private static $_instance = null;
    
    private function __construct() {
        
    }
    
    public function dispatch() {
        $router = new \MVCF\Routers\DefaultRouter;
        $router->parse();
        
        $controller = $router->getController();
        $method = $router->getMethod();
        
        if($controller == null) {
            $controller = $this->getDefaultController();
        }
        
        if ($method == null) {
            $method = $this->getDefaultMethod();
        }
        
        echo $controller . '<br>' . $method;
    }
    
    public function getDefaultController() {
        $controller = \MVCF\App::getInstance()
                ->getConfig()
                ->app['default_controller'];
        if ($controller) {
            return $controller;
        }
        return 'Index';
    }
    
    public function getDefaultMethod() {
        $method = \MVCF\App::getInstance()
                ->getConfig()
                ->app['default_method'];
        if ($method) {
            return $method;
        }
        return 'index';
    }
    /**
     * 
     * @return \GF\FrontController
     */
    
    public static function getInstance() {
        if(self::$_instance == null) {
            self::$_instance = new \MVCF\FrontController();
        }
        return self::$_instance;
    }
}

