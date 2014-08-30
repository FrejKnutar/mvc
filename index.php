<?php

require_once "initialization.php";
require_once "mysqlconnector.php";

abstract class Request {

    /**
     * Fetches a boolean variable from the request.
     * @param String $name The name of the variable that is to be fetched.
     * @param int $input [optional] <p>
     * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
     * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
     * <b>INPUT_ENV</b>. 
     * </p> Default is <b>INPUT_POST</b>
     * @return mixed Returns <b>false</b> if the variable does not exist. 
     * Returns 1 if the boolean value of the variable is true and 0 if the value
     * is false.
     */
    public static function getBool($name, $input = INPUT_POST) {
        $var = filter_input($input, $name);
        if ($var != NULL) {
            return boolval($var) ? 1 : 0;
        }
        return false;
    }

    /**
     * Fetches a floating point variable from the request.
     * @param String $name The name of the variable that is to be fetched.
     * @param int $input [optional] <p>
     * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
     * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
     * <b>INPUT_ENV</b>. 
     * </p> Default is <b>INPUT_POST</b>
     * @return mixed Returns <b>false</b> if the variable does not exist. 
     * Else returns the floating point value of the variable
     */
    public static function getFloat($name, $input = INPUT_POST) {
        $var = filter_input($input, $name);
        if ($var != NULL && is_numeric($var)) {
            return floatval($var);
        }
        return false;
    }

    /**
     * Fetches an integer variable from the request.
     * @param String $name The name of the variable that is to be fetched.
     * @param int $input [optional] <p>
     * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
     * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
     * <b>INPUT_ENV</b>. 
     * </p> Default is <b>INPUT_POST</b>
     * @return mixed Returns <b>false</b> if the variable does not exist. 
     * Else returns the integer value of the variable
     */
    public static function getInt($name, $input = INPUT_POST) {
        $var = filter_input($input, $name);
        if ($var != NULL && is_numeric($var)) {
            $val = intval($var);
            if ($val != floatval($var)) {
                return $val;
            }
        }
        return false;
    }

    /**
     * Fetches a String variable from the request.
     * @param String $name The name of the variable that is to be fetched.
     * @param int $input [optional] <p>
     * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
     * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
     * <b>INPUT_ENV</b>. 
     * </p> Default is <b>INPUT_POST</b>
     * @return mixed Returns <b>false</b> if the variable does not exist. 
     * Else returns the string value of the variable
     */
    public static function getStr($name, $input = INPUT_POST) {
        $var = filter_input($input, $name);
        if ($var != NULL) {
            return $var;
        }
        return false;
    }

}

/**
 * The base class for the controllers of the MVC model. The controller hanldes 
 * input and output, controlls the overall flow of the MVC and uses models to 
 * store data and presents views as an interface to the user.
 */
abstract class Controller {

    public function __construct() {
        
    }

    /**
     * The controller base and sets the class parameters.
     * Initializes
     */
    public static function init() {
        
    }

    /**
     * <p>The method that will be called by the system if no other method is 
     * given.</p>
     * <p> Methods are called by <b>HomeURL/<i>Controller</i>/<i>method</i></b>
     */
    public function index() {
        
    }

    /**
     * <p>Tries to load a Model into the Controller.</p>
     * <p>Models are classes that have connections to the database and store
     * data specific to an object</p>
     * @param string $name The name of the model file without the php 
     *                     extension.
     * @param array $args  An array containing arguments that are to be inputted
     *                     to the model's constructor.
     * @return mixed       Returns an instance of the model if it could be 
     *                     loaded, else <b>false</b>.
     */
    protected final function loadModel($name, array $args = array()) {
        return Loader::loadModel($name, $args);
    }

    /**
     * <p>Loads the corresponding view from the view folder.</p>
     * <p>Views are output centric PHP files that displays the user 
     * interface.</p>
     * @param string $name The name of the view.
     * @param array $vars  <p>An assiciative array that will be extracted so 
     *                     that the values of the array can be accessed by the 
     *                     view.</p><p><b>Example: </b>The value <i>'bar'</i> in
     *                     the array <b>array</b>(<i>'foo'</i> => <i>'bar'</i>)
     *                     can be accessed by <b><i>$foo</i></b> in the view.
     *                     </p>
     * @return boolean     <b>true</b> if the view could be loaded, else
     *                     <b>false</b>.
     */
    protected function loadView($name, array $vars = array()) {
        return Loader::loadView($name, $vars);
    }

    /**
     * Dispatches the request to another controller class; creating an 
     * instance of that object and calling the inputted method.
     * @param type $controller The name of the controller that the request it 
     *                         to be dispatched to.
     * @param type $method     The name method of the controller that is to be 
     *                         called. The default method is the constructor.
     * @param array $vars      The arguments that are to be inputted to the 
     *                         method of the controller.
     * @return mixed           Returns the instance of the Controller if it 
     *                         could be instanced and the method could be 
     *                         called, else returns <b>false</b>.
     */
    protected function dispatch(
    $controller, $method = "__construct", array $vars = array()) {
        return Loader::loadController($controller, $method, $vars);
    }

}

/**
 * Class that loads Controllers, Models, Views and Plugins.
 */
class Loader {

    /** <i>The</i> instance of Loader class
     * @var Loader
     */
    private static $loader;

    /** The location of the root directory of the MVC.
     * @var String 
     */
    private static $controllerDir = CONTROLLER_DIR;

    /** The name of the directory containing the models
     * @var String
     */
    private static $modelDir = MODEL_DIR;

    /** The name of the directory containing the plugins
     * @var String
     */
    private static $pluginDir = PLUGIN_DIR;

    /** The name of the directory containing the views
     * @var String
     */
    private static $viewDir = VIEW_DIR;

    /** Constructor */
    private function __construct() {
        
    }

    /**
     * Returns <i>the</i> instance of the Loader class.
     * @return Loader <i>the</i> Loader instance.
     */
    static function getInstance() {
        if (self::$loader == NULL) {
            self::$loader = new Loader();
        }
        return self::$loader;
    }

    /**
     * <p>Initializes the Loader, assigning values to the static proporties.</p>
     * <p>Dispatches the request given the request URL of the user.</p>
     */
    static function init() {
        $args = preg_split(
                '@/@', filter_input(INPUT_SERVER, 'PATH_INFO'), -1, PREG_SPLIT_NO_EMPTY
        );
        $controller = empty($args) ? DEFAULT_CONTROLLER : array_shift($args);
        $method = empty($args) ? DEFAULT_METHOD : array_shift($args);
        if (substr($method, 0, 2) != "__") {
            if (self::loadController($controller, $method, $args) !== false) {
                return;
            }
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Loads a PHP file from the path relative to the base of the MVC.
     * @param String  $path the path that is to be appended to the relative path.
     * @param boolean $require_once
     * <p><b>true</b> if the file should be fetched with the 
     * @return boolean <b>true</b> if the file could be fethced, else 
     * <b>false</b>
     */
    static private function loadFile($path) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $path . '.php';
        if (!file_exists($file)) {
            return false;
        }
        require_once $file;
        return true;
    }

    /**
     * Loads a controller and calls the method with the input arguments.
     * @param String $controller The name of the controller that is to be 
     *                           loaded.
     * @param String $method The name of method of the controller that is to be
     *                       callled.
     * @param array $args    The arguments that the method should be called 
     *                       with.
     * @return mixed <p>Returns the controller if it could an instance could be 
     *               created and the method could be called with the arguments.
     *               </p><p>Else returns <b>false</b>.</p>
     */
    static function loadController(
    $controller, $method = DEFAULT_METHOD, array $args = array()) {
        if (self::loadFile(self::$controllerDir . DIRECTORY_SEPARATOR . $controller) && class_exists($controller)) {
            $refClass = new ReflectionClass($controller);
            if (!$refClass->isSubclassOf("Controller")) {
                return false;
            }
            if ($method == "__construct" && is_instantiable($controller, $method, count($args))) {
                $controller = $refClass->newInstance($args);
                return $controller;
            } elseif (is_instantiable($controller, "__construct") && is_instantiable($controller, $method, count($args))) {
                $controller = $refClass->newInstance();
                call_user_func_array(array($controller, $method), $args);
                return $controller;
            }
        }
        return false;
    }

    /**
     * Loads the model class and creates an instance of the model; 
     * calling the constructor of the model class with the argument array.
     * @param String $model The name of the model that is to be loaded.
     * @param array $args   The arguments that the constructor of the model 
     *                      should be called with.
     * @return mixed <p>Returns the model if an instance could be by calling 
     *               it's constructor with the input arguments.</p><p>Else 
     *               returns <b>false</b>.</p>
     */
    static function loadModel($model, array $args = array()) {
        if (!self::loadFile(self::$modelDir . DIRECTORY_SEPARATOR . $model)) {
            return false;
        }
        if (!class_exists($model)) {
            return false;
        }
        if (is_instantiable($model, "__construct", count($args))) {
            return false;
        }
        return (new ReflectionClass($model))->newInstance($args);
    }

    /**
     * <p>Loads a plugin, checking if the version of the plugin against the 
     * required min and max version if they are inputted.</p><p>Version is of 
     * sequence form <b>X</b>[<b>.Y</b>[<b>.Z</b>[<b>...</b>]]</b> where 
     * <b>X</b>, <b>Y</b> and <b>Z</b> are positive integers, <b>X</b> is the 
     * major version and following sequences are minor versions in descending 
     * order.</p><p>Minor versions, such as <b>Y</b> and <b>Z</b>, are optional.
     * As many squences as wanted can be given, indicated by [<b>...</b>].
     * @param String $name The name of the plugin that is to be loaded.
     * @param String $minVer The minimum version of the plugin.
     * @param String $maxVer The maximum version of the plugin.
     * @return boolean
     */
    static function loadPlugin($name, $minVer = NULL, $maxVer = NULL) {
        if (($data = self::loadPluginData($name)) == false) {
            return false;
        }
        $ver = isset($data->version) ? $data->version : NULL;
        if (!self::versionCheck($minVer, $ver, $maxVer)) {
            return false;
        }
        $reqs = isset($data->requirements) ? $data->requirements : array();
        foreach ($reqs as $requirement) {
            $min = isset($requirement->minVersion) ? $requirement->minVersion : NULL;
            $max = isset($requirement->maxVersion) ? $requirement->maxVersion : NULL;
            self::loadPlugin($requirement->name, $min, $max);
        }
        return self::loadFile(self::$pluginDir . DIRECTORY_SEPARATOR
                        . $name . DIRECTORY_SEPARATOR . $name);
    }

    /**
     * Returns the JSON information of a plugin.
     * @param String $name the name of the plugin.
     * @return mixed A JSON object of the data if one could be loaded, else 
     *         <b>false</b>.
     */
    static function loadPluginData($name) {
        $d = DIRECTORY_SEPARATOR;
        $name = strtolower($name);
        $f = dirname(__FILE__) . $d . self::$pluginDir . "$d$name$d$name.json";
        if (file_exists($f)) {
            $json = json_decode(file_get_contents($f));
            return $json;
        }
        return false;
    }

    /**
     * Extracts the input assiciative array and loads the view.
     * @param String $view the name of the view that is to be loaded.
     * @param String $vars The assiciative array containing the variables that
     *                     are to be extracted.
     * @return boolean <b>true</b> if the view could be loaded, else 
     *                 <b>false</b>.
     */
    static function loadView($view, array $vars = array()) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::$viewDir
                . DIRECTORY_SEPARATOR . $view . '.php';
        if (!file_exists($file)) {
            return false;
        }
        extract($vars);
        require $file;
        return true;
    }

    /**
     * <p>Checks the version strings against each other</p><p>Version is of 
     * sequence form <b>X</b>[<b>.Y</b>[<b>.Z</b>[<b>...</b>]]</b> where 
     * <b>X</b>, <b>Y</b> and <b>Z</b> are positive integers, <b>X</b> is the 
     * major version and following sequences are minor versions in descending 
     * order.</p><p>Minor versions, such as <b>Y</b> and <b>Z</b>, are optional.
     * As many squences as wanted can be given, indicated by [<b>...</b>].
     * @param String $min The String containing the min version.
     * @param String $cur The Strung containing the current version.
     * @param String $max The String cintaining the max version.
     * @return boolean <b>TRUE</b> if the cur version is in between the min and
     *                 max versions, else <b>FALSE</b>.
     */
    private static function versionCheck($min, $cur, $max) {
        $minArray = is_string($min) ? preg_split('/\./', $min, -1, PREG_SPLIT_NO_EMPTY) : array();
        $curArray = is_string($cur) ? preg_split('/\./', $cur, -1, PREG_SPLIT_NO_EMPTY) : array();
        $maxArray = is_string($max) ? preg_split('/\./', $max, -1, PREG_SPLIT_NO_EMPTY) : array();
        if (empty($minArray) && empty($maxArray)) {
            return true;
        }
        $count = max(array(
            count($minArray),
            count($curArray),
            count($maxArray))
        );
        for ($i = 0; $i < $count; $i++) {
            $l = isset($minArray[$i]) ? intval($minArray[$i]) : 0;
            $c = isset($curArray[$i]) ? intval($curArray[$i]) : 0;
            $h = isset($maxArray[$i]) ? intval($maxArray[$i]) : 0;
            if ((empty($maxArray) || $h > $c) && ($c > $l) || empty($minArray)) {
                return true;
            } else if ($h < $c || $c < $l) {
                return false;
            }
        }
        return false;
    }

}

Loader::init();
