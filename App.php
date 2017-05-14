<?php
namespace MVCF;
include_once 'Loader.php';

class App {
    
    private static $_instance = null;
    private $_config = null;
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
