<?php
namespace MVCF;
include_once 'Loader.php';

class App {
    
    private static $_instance = null;
    
    private function __construct() {
        \MVCF\Loader::registerNamespace('MVCF', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \MVCF\Loader::registerAutoload();
    }
    
    public function run() {
        
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
