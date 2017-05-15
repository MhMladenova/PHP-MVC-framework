<?php
namespace MVCF;

class FrontController {
    
    private static $_instance = null;
    private $_router = null;
    private $_namespace = null;
    private $_controller = null;
    private $_method = null;
    
    private function __construct() {
        
    }
    
    function get_router() {
        return $this->_router;
    }

    function set_router(\MVCF\Routers\IRouter $_router) {
        $this->_router = $_router;
    }

        
    public function dispatch() {
        if ($this->_router == null) {
            throw new \Exception ('No valide router found', 500);
        }
        
        $_uri = $router->getURI();
        $routes = \MVCF\App::getInstance()
                ->getConfig()
                ->routes;
        $_rc = null;
        
        if (is_array($routes) && count($routes) > 0) {
            foreach($routes as $key => $val) {
                if (stripos($_uri, $key) === 0 
                        && ($_uri == $key || strpos($_uri, $key . '/') === 0 )
                        && $val['namespace']) {
                    $this->_namespace = $val['namespace'];
                    $_uri = substr($_uri, strlen($key) + 1);
                    $_rc = $val;
                    break;
                }
            }
        } else {
            //TODO
            throw new \Exception('Default route missing', 500);
        }
        
        if ($this->_namespace == null && $routes['*']['namespace']) {
            $this->_namespace = $routes['*']['namespace'];
            $_rc = $routes['*'];
        } else if ($this->_namespace == null && !$routes['*']['namespace']) {
            //TODO
            throw new \Exception('Default route missing', 500);
        }
        
        $_params = explode('/', $_uri);
        if ($_params[0]) {
            $this->_controller = strtolower($_params[0]);
            
            //if controller and method are missing, there are no params too
            if ($_params[1]) {
                $this->_method = strtolower($_params[1]);
            } else {
                $this->_method = $this->getDefaultMethod();
            }
        } else {
            $this->_controller = $this->getDefaultController();
            $this->_method = $this->getDefaultMethod();
        }
        
        if(is_array($_rc) && $_rc['controllers']) {
            if($_rc['controllers'][$this->_controller]['methods'][$this->method]) {
                $this->_method = strtolower($_rc['controllers'][$this->_controller]['methods'][$this->method]);
            }
            if (isset($_rc['controllers'][$this->_controller]['to'])) {
                $this->_controller = strtolower($_rc['controllers'][$this->_controller]['to']);
            }   
        }
        
        $controller = $this->_namespace . '\\' . ucfirst($this->_controller);
        $newController = new $controller();
        $newController->{$this->_method}();
    }
    
    public function getDefaultController() {
        $controller = \MVCF\App::getInstance()
                ->getConfig()
                ->app['default_controller'];
        if ($controller) {
            return strtolower($controller);
        }
        return 'Index';
    }
    
    public function getDefaultMethod() {
        $method = \MVCF\App::getInstance()
                ->getConfig()
                ->app['default_method'];
        if ($method) {
            return strtolower($method);
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

