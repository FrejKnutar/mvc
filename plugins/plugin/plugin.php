<?php
use mysqli;
class Plugin
{
    private $connection;
    private static $tableName = "plugin_variables";
    
    function __construct(mysqli $connection) {
        $this->connection = $connection;
        $this->init();
    }
    
    protected function __get($name) {
        return $this->getVar($name);
    }
    
    protected function __set($name, $value) {
        return $this->setVar($name, $value);
    }
    
    public static function install(mysqli $connection) {
        $query = "CREATE TABLE IF NOT EXISTS `" . self::$tableName . "` (" . 
                 "`name` TEXT NOT NULL, " . 
                 "`value` TEXT NOT NULL, " .
                 "PRIMARY KEY(`name`)), " .
                 "DEFAULT CHARACTER SET = utf8 COLLATE = utf8_bin";
        $statement = $connection->prepare($query);
        $statement->execute();
    }
    
    protected function init() {
        
    }
    
    protected final function getVar($name) {
        $query = "SELECT value from plugin_variables WHERE name=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', get_class($this) . "_$name");
        $statement->execute();
        $value = "";
        $statement->bind_result($value);
        $statement->fetch();
        return $value;
    }
    
    protected final function setVar($name, $value) {
        $query = "REPLACE INTO `" . self::$tableName . "` " . 
                 "SET value=? WHERE name=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', $value);
        $statement->bind_param('s', get_class($this) . "_$name");
        $statement->execute();
        return $value;
    }
    
    protected final function getVars() {
        $query = "SELECT name, value FROM `" . self::$tableName . "` " .
                 "WHERE name like ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', get_class($this) . "\_");
        $statement->execute();
        $name = "";
        $value = "";
        $returnArray = array();
        $statement->bind_result($name, $value);
        while ($statement->fetch()) {
            $returnArray[$name] = $value;
        }
        return $returnArray;
    }
}