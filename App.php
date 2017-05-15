<?php
namespace MVCF;
include_once 'Loader.php';

class App {
    
    private static $_instance = null;
    private $_config = null;
    private $_router = null;
    private $_dbConnections = array();
    
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
    
    public function getRouter() {
        return $this->_router;
    }

    public function setRouter($_router) {
        $this->_router = $_router;
    }

    public function getDBConnection($connection = 'default') {
        if(!$connection) {
            //TODO
            throw new \Exception('No connection identifier provided', 500);
        }
        if ($this->_dbConnections[$connection]) {
            return $this->_dbConnections[$connection];
        }
        
        $cnfData = $this->getConfig()->database;
        if (!$cnfData[$connection]) {
            //TODO
            throw new \Exception('No valid connection identifier proviede', 500);
        }
        
        $dbh = new \PDO($cnfData[$connection]['connection_uri'], 
                $cnfData[$connection]['username'],
                $cnfData[$connection]['password'],
                $cnfData[$connection]['pdo_options']);
        $this->_dbConnections[$connection] = $dbh;
        
        return $dbh;
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
