<?php
require_once("initialization.php");
/**
 * Replace the first occurrencee of the search string with the replacement 
 * string.
 * @param string $search <p>
 * The value being searched for, otherwise known as the needle.
 * </p>
 * @param string $replace <p>
 * The replacement value that replaces found <i>search</i> values.
 * </p>
 * @param string $subject <p>
 * The string being searched and replaced on, otherwise known as the haystack.
 * </p>
 * </p>
 * @return string This function returns the string with the replaced value.
 */
function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
/**
 * Removes the first occurence of the search string.
 * @param string $haystack The string being searched and removed from.
 * @param string $needle The value being searched from.
 * @return string This function returnes the string the removed value.
 */
function str_remove_first($haystack, $needle) {
    return str_replace_first($needle, '', $haystack);
}

/**
 * Loads and initializes a plugin. Plugins are located in the plugin-folder
 * @param type $name the name of the plugin.
 * @throws Exception Throws an exception if the plugin could not be loaded.
 */
function requirePlugin($name, $minVer = NULL, $maxVer = NULL) {
    $d = DIRECTORY_SEPARATOR;
    $name = strtolower($name);
    $path = dirname(__FILE__) . $d . "plugins$d$name$d$name.php";
    if (!file_exists($path)) {
        throw new Exception("Plugin '$name' does not exist at location "
        . "'$path'.");
    } elseif ($minVer != NULL || $maxVer || NULL) {
        $json = pluginInfo($name);
        $ver = isset($json->version) ? $json->version : NULL;
        if ($minVer != NULL && !versionCheck($minVer, $ver)) {
            throw new Exception("Plugin '$name' is outdated. A version later "
                    . "than $minVer is required, installed version is $ver.");
        } elseif ($maxVer != NULL && !versionCheck($ver, $maxVer)) {
            throw new Exception("Plugin '$name' is too new. A version previous "
                    . "to $maxVer is required, installed version is $ver.");
        }
    }
    require_once($path);
}
/**
 * Checks that the version of the first string is less than the version of the
 * second string. Version is of the type X[.Y[.Z[...]]] where X, Y and Z are 
 * positive integers. If the first integer between the two inputs that differs
 * is smaller or missing in the second parameter false is returned. Else true is
 * returned. If all integers are equal true is returned.
 * @param string $minVer
 * @param string $maxVer
 * @return boolean
 */
function versionCheck($minVer, $maxVer) {
    $minVer = $minVer != NULL ? explode('.', $minVer) : array(0);
    $maxVer = $minVer != NULL ? explode('.', $maxVer) : array(0);
    $max = count($minVer) > count($maxVer) ? $minVer : $maxVer;
    for ($i = 0; $i < $max; $i++) {
        if (!isset($minVer[$i]) && !isset($maxVer[$i])) {
            return true;
        } elseif (!isset($minVer[$i])) {
            return true;
        } elseif (!isset($maxVer[$i])) {
            return false;
        } elseif ($minVer[$i] > $maxVer[$i]) {
            return false;
        } elseif ($minVer[$i] < $maxVer[$i]) {
            return true;
        }
    }
    return false;
}
/**
 * Returns the JSON information of the plugin with the parameter name.
 * @param string $name the name of the plugin. Null is returned if no data was
 * fetched.
 * @return JSON An object that 
 * @throws Exception Throws an exception if the file does not exist.
 */
function pluginInfo($name) {
    $d = DIRECTORY_SEPARATOR;
    $name = strtolower($name);
    $path = dirname(__FILE__) . $d . "plugins$d$name$d$name.json";
    if (file_exists($path)) {
        $json = json_decode(file_get_contents($path));
        return $json;
    } else {
        throw new Exception("Plugin '$name' does not exist at location "
        . "'$path'.");
    }
    return NULL;
}

/**
 * The base class for the controllers of the MVC model. The controller is 
 * capable of loading views and models.
 */
abstract class Controller {

    /**
     * @var string The directory name where the models are located.
     */
    private static $modeldir = "models";

    /**
     * @var string The directory name where the views are located. 
     */
    private static $viewdir = "views";

    /**
     * @var string The file path to this document.
     */
    protected static $root = null;

    /**
     * Initializes the controller base and sets the class parameters.
     */
    private static function init() {
        self::$root = dirname(__FILE__);
        $docRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }

    /**
     * The standard method that is called if no other method is called.
     */
    public function index() {
        
    }

    /**
     * Tries to load the Model with the input model name.
     * @param string $name The name of the model
     * @throws Exception Throws an exception if the model does not exist.
     */
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

    /**
     * Loads the corresponding view from the view folder. The input array will
     * extracted and the values can be accessed within the view by calling the
     * key as a PHP variable.
     * @param string $name The name 
     * @param array $array the array of vairables that is to be extracted.
     *                     The values of the array can be reached by calling the
     *                     key as a PHP variable. Example the value of the key
     *                     in array array("key"=>value) can be reached by $key.
     * @throws Exception Throws an exception if the view does not exist.
     */
    protected function loadView($name, array $array = array()) {
        if (self::$root == null) {
            self::init();
        }
        $d = DIRECTORY_SEPARATOR;
        $path = self::$root . $d . self::$viewdir . $d . $name . ".php";
        if (file_exists($path)) {
            if (!empty($array)) {
                extract($array);
            }
            include($path);
        } else {
            throw new Exception("View '$name' does not exist.");
        }
    }

}

$argv = array();
if (DIRECTORY_SEPARATOR == '/') {
    $argv[0] = str_remove_first(
        __FILE__, 
        filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')
    );
} else {
    $argv[0] = str_remove_first(
        str_replace(
            '\\', 
            '/', 
            __FILE__
        ),
        filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')
    );
}
if ($argv[0]{0} != '/') {
    $argv[0] = '/' . $argv[0];
}
define(
    "HOME",
    'http'  . (filter_input(INPUT_SERVER, "HTTPS") == "on" ? "s" : '')
        . "://" . filter_input(INPUT_SERVER, "SERVER_NAME") . 
    (filter_input(INPUT_SERVER, "SERVER_PORT") != 80 ? filter_input(INPUT_SERVER, "SERVER_PORT") : '')
        . $argv[0]
);
$argv[1] = str_remove_first(
    filter_input(INPUT_SERVER, 'REQUEST_URI'), 
    $argv[0]
);

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
    if ($class == NULL || $method == NULL) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    if (!file_exists($filepath)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    require_once($filepath);
    if (!class_exists($class)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    $refClass = new ReflectionClass($class);
    if ($refClass->isAbstract() || $refClass->isInterface()) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    $refMeth = $refClass->getMethod("__construct");
    if ($refMeth->getNumberOfRequiredParameters() > 0) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    $page = $refClass->newInstance();
    if (!is_a($page, "Controller")) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    if (method_exists($class, $method)) {
        $refMeth = new ReflectionMethod($page, $method);
        if ($refMeth->isPublic() && substr($method, 0, 2) != "__") {
            if ($refMeth->getNumberOfRequiredParameters() <= $arguments ||
                    $refMeth->getNumberOfParameters() <= $arguments) {
                call_user_func_array(array($page, $method), $arguments);
            } else {
                header("HTTP/1.0 400 Bad Request");
            }
        } else {
            header("HTTP/1.0 403 Forbidden");
        }
    } else {
        header("HTTP/1.0 501 Not Implemented");
    }
}