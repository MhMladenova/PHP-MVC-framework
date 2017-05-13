<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MVCF;
class App {
    
    private static $_instance = null;
    
    public function run() {
        echo 'runnnn!';
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
