<?php
require_once("initialization.php");
function requirePlugin($name) {
    $d = DIRECTORY_SEPARATOR;
    $name = strtolower($name);
    $path = dirname(__FILE__) . $d . "plugins" . $d . $name . $d . $name . ".php";
    if (file_exists($path)) {
        require_once($path);
    } else {
        throw new Exception("Plugin $name does not exist in path '$path'.");
    }
}
abstract class Model {
    /**
     *
     * @var Mysqli  The connection to the database
     */
    protected $connection = NULL;
     /**
      * Creates a MYSQL connection.
      * @param String $host The host name of the mysql server.
      * @param String $user The user name of the mysql server.
      * @param String $password The password to the mysql server.
      * @param String $database The database that is to be used.
      * @throws Exception The error that occured when connecting to the database
      */
    function __construct($host, $user, $password, $database) {
        $this->connection = new mysqli($host, $user, $password, $database);
        if (mysqli_connect_errno()) {
            throw new Exception("MYSQLI Connection error: " . mysqli_connect_error());
        }
    }
    
    /**
     * Closes the opened mysql connection once the object has been removed.
     */
    public function __destruct() {
        $this->close();
    }
    
    /**
     * Closes the opened mysql connection.
     */
    public function close() {
        if ($this->connection != null) {
            mysqli_close($this->connection);
        }
    }

}

abstract class Controller {
    private $modeldir = "models";
    private $viewdir = "views";
    private static $home = null;
    private static $root = null;
    
    function __construct() {}

    public function __destruct() {}

    private static function init() {
        self::$root = dirname(__FILE__);
        $docRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        if (DIRECTORY_SEPARATOR === '/') {
            self::$home = str_replace($docRoot, "", self::$root);
        } else {
            self::$home = str_replace($docRoot, "", str_replace(self::$root, "\\", '/'));
        }
        self::$home .= '/';
    }
    
    public function index() {
        
    }
    
    protected function loadModel($name) {
        if (self::$home == null || self::$root == null) {
            self::init();
        }
        $d = DIRECTORY_SEPARATOR;
        $path = self::$root . $d . $this->modeldir . $d . $name . ".php";
        if (file_exists($path)) {
            require_once($path);
            $this->_properties[$name] = new $name();
        } else {
            throw new Exception("Model $name does not exist.");
        }
    }

    protected function loadView($name, $array = array()) {
        if (self::$home == null || self::$root == null) {
            self::init();
        }
        $d = DIRECTORY_SEPARATOR;
        $path = self::$root . $d . $this->viewdir . $d . $name . ".php";
        if (file_exists($path)) {
            extract($array);
            include($path);
        } else {
            throw new Exception("View $name does not exist.");
        }
    }

}
$docRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
$reqUri = filter_input(INPUT_SERVER, 'REQUEST_URI');
if ($docRoot != null && $reqUri != null) {
    $argv = array(0 => null, 1 => null);
    $argv[0] = '/';
    if (DIRECTORY_SEPARATOR == '/') {
        $argv[0] .= str_replace($docRoot, "", __FILE__);
    } else {
        $argv[0] .= str_replace($docRoot, "", str_replace('\\', '/', __FILE__));
    }
    $argv[1] = str_replace($argv, "", $reqUri);
    unset($docRoot);
    unset($reqUri);
}

if (isset($argv)) {
    $arguments = array();
    $class = null;
    $method = null;
    $temp = explode("?", $argv[1]);
    $argv[1] = $temp[0];
    if (count($temp) > 1) {
        $argv[2] = $temp[1];
    }
    unset($temp);
    foreach (explode("/", $argv[1]) as $str) {
        if ($str == "" || $str == null) {
            continue;
        } elseif ($class == null) {
            $class = $str;
        } elseif ($method == null) {
            $method = $str;
        } else {
            $arguments[] = $str;
        }
    }
    if ($class == NULL) {
        $class = DEFAULT_CLASS;
    }
    if ($method == NULL) {
        $method = DEFAULT_METHOD;
    }
    $controllerdir = "controllers";
    $d = DIRECTORY_SEPARATOR;
    $filepath = dirname(__FILE__) . $d . $controllerdir . $d . $class . ".php";
    unset($d);
    unset($argv);
    if (file_exists($filepath)) {
        require_once($filepath);
        if (class_exists($class)) {
            $refClass = new ReflectionClass($class);
            if (!$refClass->isAbstract() && !$refClass->isInterface()) {
                $page = new $class();
                if ($method != null) {
                    if (method_exists($class, $method)) {
                        $refMeth = new ReflectionMethod($page, $method);
                        if ($refMeth->isPublic() && substr_count($method, "__", 0, 2) == 0) {
                            call_user_func_array(array($page, $method), $arguments);
                        } else {
                            header("HTTP/1.0 403 Forbidden");
                        }
                    } else {
                        header("HTTP/1.0 501 Not Implemented");
                    }
                }
            }
        }
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}