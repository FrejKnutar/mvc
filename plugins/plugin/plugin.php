<?php

class Plugin {

    /** The mysqli connection.
     * @var mysqli 
     */
    private $connection = NULL;

    /** The name of the plugin table in the database
     * @var String 
     */
    private static $tableName = PLUGIN_TABLE;
    private static $tablePrefix = TABLE_PREFIX;
    /**
     * Constructor that opens an mysqli connection.
     */
    function __construct() {
        $this->connection = mysqliconnection();
    }

    /**
     * Destructor that closes the mysqli connection.
     */
    function __destruct() {
        if ($this->connection != NULL) {
            $this->connection->close();
        }
    }

    /**
     * Installs the plugin, creating the tables necessary.
     */
    public static function install() {
        $con = mysqliconnection();
        $query = "CREATE TABLE IF NOT EXISTS `" . self::$tableName . "` ("
                . "`name` TEXT NOT NULL, "
                . "`type` VARCHAR(1) NOT NULL DEFAULT 's'"
                . "`value` TEXT NOT NULL, "
                . "PRIMARY KEY(`name`)), "
                . "DEFAULT CHARACTER SET = utf8 COLLATE = utf8_bin";
        $statement = $con->prepare($query);
        $statement->execute();
        $con->close();
    }

    /**
     * <p>Retireves a plugin variable in the database.</p><p>Variables are 
     * unique to a plugin; two plugins can therefore store variables with the 
     * same name.</p>
     * @param String $name The name of the variable.
     * @return mixed Returns the variable if it exists. Returns <b>NULL</b> if 
     *               it doesn't.
     */
    protected final function get($name) {
        $query = "SELECT value from mvc_" . self::$tableName . " WHERE name=?";
        $statement = $this->connection->prepare($query);
        if ($statement !== false) {
            $className = get_class($this) . "_$name";
            $statement->bind_param('s', $className);
            $statement->execute();
            if ($statement->num_rows() > 0) {
                $value = "";
                $type = "";
                $statement->bind_result($type, $value);
                $statement->fetch();
                return unserialize($value);
            }
        }
        return NULL;
    }

    /**
     * <p>Saves a plugin variable in the database.</p><p>Variables are unique 
     * to a plugin; two plugins can therefore store variables with the same 
     * name.</p>
     * @param String $name The name of the variable.
     * @param mixed $var The variable that is to be saved.
     * @return boolean <b>TRUE</b> if the variable could be saved, else 
     * <b>FALSE</b>.
     */
    protected final function set($name, $var) {
        $query = "REPLACE INTO mvc_" . self::$tableName . " (name, value) "
                . "VALUES (?,?)";
        $statement = $this->connection->prepare($query);
        if ($statement !== false) {
            $serVar = serialize($var);
            $className = get_class($this) . "_$name";
            $statement->bind_param('ss', $className, $serVar);
            $statement->execute();
            return $statement->affected_rows > 0;
        }
        return false;
    }

    /**
     * Retrieves all plugin specific variables from the database.
     * @param boolean $array <b>TRUE</b> if the return value should be an 
     *                       associative array. <b>FALSE</b> indicates that the
     *                       return value should be an object.
     * @return mixed <p>An associative array or object containing the values.
     *               </p><p>The keys of the array, the objects properties, are 
     *               the name of the variables while the values are the 
     *               variables values.</p>
     */
    protected final function getAll($array = true) {
        $query = "SELECT name, value FROM `" . self::$tableName . "` "
                . "WHERE name like ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', get_class($this) . '_');
        $statement->execute();
        if ($statement->num_rows() > 0) {
            $name = "";
            $value = "";
            $returnArray = array();
            $statement->bind_result($name, $value);
            while ($statement->fetch()) {
                $returnArray[$name] = unserialize($value);
            }
        }
        if ($array) {
            return $returnArray;
        } else {
            return (object) $returnArray;
        }
    }

}
