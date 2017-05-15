<?php
namespace MVCF;
include_once 'Loader.php';

class App {
    
    private static $_instance = null;
    private $_config = null;
    private $_router = null;
    
    /**
     *
     * @var type \MVCF\FrontController
     */
    private $_frontController = null;
    
    private function __construct() {
        \MVCF\Loader::registerNamespace('MVCF', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \MVCF\Loader::registerAutoload();
        $this->_config = \MVCF\Config::getInstance();
        
        if ($this->_config->getConfigFolder() == null) {
            $this->setConfigFolder('..\config');
        }
    }
    
    public function setConfigFolder($path) {
        $this->_config->setConfigFolder($path);
    }
    
    public function getConfigFolder() {
        return $this->_configFolder;
    }
    
    function get_router() {
        return $this->_router;
    }

    function set_router($_router) {
        $this->_router = $_router;
    }

        
    /**
     * 
     * @return \MVCF\Config
     */
    public function getConfig() {
        return $this->_config;
    }
    
    public function run() {
        //if config folder is not set, use default one
        if ($this->_config->getConfigFolder() == null) {
            $this->setConfigFolder('..\config');
        }
        
        $this->_frontController = \MVCF\FrontController::getInstance();
        if ($this->_router instanceof \MVCF\Routers\IRouter) {
            $this->_frontController->setRouter($this->_router);
        } else if($this->_router == 'JsonRPCRouter') {
            //TODO fix after creating RPC router 
            $this->_frontController->setRouter(new \MVCF\Routers\DefaultRouter());
        } else if($this->_router == 'CLIRouter') {
            //TODO fix after creating CLI router 
            $this->_frontController->setRouter(new \MVCF\Routers\DefaultRouter());
        } else {
            $this->_frontController->setRouter(new \MVCF\Routers\DefaultRouter());
        }
        $this->_frontController->dispatch();
    }
    
    /**
     * 
     * @return \MVCF\App
     */
    public static function getInstance() {
        if(self::$_instance == null) {
            self::$_instance = new \MVCF\App();
        }
        return self::$_instance;
    }
}
