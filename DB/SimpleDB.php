<?php
namespace MVCF\DB;

class SimpleDB {
    
    protected $_connection = 'default';
    private $_db = null;
    
    private $_stmt = null;
    private $_params = array();
    private $_sql;
    
    public function __construct($connection = null) {    
        if($connection instanceof \PDO) {
            $this->_db = $connection;
        } else if ($connection != null) {
            $this->_db = \MVCF\App::getInstance()->getDBConnection($connection);
            $this->_connection = $connection;
        } else {
            $this->_db = \MVCF\App::getInstance()->getDBConnection($this->_connection);
        }
    }
    
    /**
     * 
     * @param type $sql
     * @param type $params
     * @param type $pdoOptions
     * @return \MVCF\DB\SimpleDB
     */
    public function prepare($sql, $params = array(), $pdoOptions = array()) {
        var_dump($this->_db);
        $this->_stmt = $this->_db->prepare($sql, $pdoOptions);
        $this->_params = $params;
        $this->_sql = $sql;
        
        return $this;
    }
    
    public function execute($params = array()) {
        if ($params && !empty($params)) {
            $this->_params = $params;
        }
        //if ($this->_logSQL) {
        //    \MVCF\Logger::getInstance()
        //            ->set($this->_sql . ' ' . print_r($this->_params, true), 'db');
        //}
        $this->_stmt->execute($this->_params);
        
        return $this;
    }
    
    public function fetchAllAssoc() {
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function fetchRowAssoc() {
        return $this->_stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function fetchAllNum() {
        return $this->_stmt->fetchAll(\PDO::FETCH_NUM);
    }
    
    public function fetchRowNum() {
        return $this->_stmt->fetch(\PDO::FETCH_NUM);
    }
    
    public function fetchAllOb() {
        return $this->_stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function fetchRowOb() {
        return $this->_stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    public function fetchAllColumn($column) {
        return $this->_stmt->fetchAll(\PDO::FETCH_COLUMN, $column);
    }
    
    public function fetchRowColumn($column) {
        return $this->_stmt->fetch(\PDO::FETCH_COLUMN, $column);
    }
    
    public function fetchAllClass($class) {
        return $this->_stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }
    
    public function fetchRowClass($class) {
        return $this->_stmt->fetch(\PDO::FETCH_CLASS, $class);
    }
    
    public function getLastInsertId() {
        return $this->_db->lastInsertId();
    }
    
    public function getAllAffectedRows() {
        return $this->_stmt->rowCount();
    }
    
    public function getSTMT() {
        return $this->_stmt;
    }
}

