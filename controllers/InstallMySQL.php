<?php

class InstallMySQL extends Controller {

    private $viewFolder = 'install/';
    private $mysqlFilePath;
    private $errors = array();
    private $host = "localhost";
    private $database = "mvc";
    private $prefix = "mvc_";
    private $port = 3306;
    private $user = "root";
    private $password = '';
    private $pluginUser = "plugin";
    private $pluginPass = NULL;

    public function __construct() {
        $this->mysqlFilePath = realpath(
                "." . DIRECTORY_SEPARATOR
                . "mysqlconnector.php");
        if (!file_exists($this->mysqlFilePath)) {
            touch($this->mysqlFilePath);
        }
    }

    private function getPostVars() {
        $errorStr = "Please enter a %s.";
        if (($this->host = Request::getStr('host')) === false || strlen($this->host) == 0) {
            $this->errors['host'] = sprintf($errorStr, "host name");
        }
        if (($this->user = Request::getStr('user')) === false) {
            $this->errors['user'] = sprintf($errorStr, "user");
        }
        if (($this->database = Request::getStr('database')) === false) {
            $this->errors['database'] = sprintf($errorStr, "database");
        }
        if (($this->port = Request::getInt('port')) === false && $this->port < 0) {
            $this->errors['port'] = sprintf($errorStr, "valid port");
        }
        if (($this->pluginUser = Request::getStr('pluginuser')) === false) {
            $this->errors['pluginUser'] = sprintf($errorStr, "plugin user");
        }
        if (($this->pluginPass = Request::getStr('pluginpass')) === false) {
            $this->pluginPass = substr(md5(rand()), rand(0, 31 - 15), 15);
        }
        $this->password = Request::getStr('password');
        $this->prefix = Request::getStr('prefix');
        return empty($this->errors);
    }

    public function index() {
        $nav = array("MySQL install");
        $this->loadView($this->viewFolder . "installmysql", array(
            'method' => 'MySQL setup',
            'host' => $this->host,
            'database' => $this->database,
            'prefix' => $this->prefix,
            'port' => $this->port,
            'user' => $this->user,
            'plugin' => $this->pluginUser,
            'nav' => $nav,
            'action' => get_url(get_class($this) . '/mysqlPost'),
            'errors' => $this->errors
                )
        );
    }

    private function initMysqlFile() {
        $t = "    ";
        $contents = "<?php\n\nfunction mysqliconnection() {\n" . $t
                . "\$trace = debug_backtrace();\n" . $t . "if (DIRECTORY_SEPARA"
                . "TOR == '/') {\n" . $t . $t . "\$dirname = dirname(\$trace[0]"
                . "['file']);\n" . $t . "} else {\n" . $t . $t . "\$dirname = s"
                . "tr_replace('\\\', '/', dirname(\$trace[0]['file']));\n" . $t
                . "}\n" . $t . "\$path = str_remove_first(\n" . $t . $t
                . "\$dirname, dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENA"
                . "ME'))\n" . $t . ");\n" . $t . "\$folder = strstr(\$path, '/'"
                . ", true);\n" . $t . "if (\$folder == PLUGIN_DIR || \$folder ="
                . "= '/' . PLUGIN_DIR) {\n" . $t . $t . "\$user = \""
                . $this->pluginUser . "\";\n" . $t . $t . "\$password = \""
                . $this->pluginPass . "\";\n" . $t . "} else {\n" . $t . $t
                . "\$user = \"" . $this->user . "\";\n" . $t . $t
                . "\$password = \"" . $this->password . "\";\n" . $t
                . "}\n" . $t . "return new mysqli(\"localhost\", \$user, \$pass"
                . "word, \"" . $this->database . "\", " . $this->port . ");\n}";
        file_put_contents($this->mysqlFilePath, $contents);
    }

    public function mysqlPost() {
        if (empty($_POST)) {
            $this->index();
        } else {
            $this->getPostVars();
            if (empty($this->errors) &&
                    $this->checkConnection() &&
                    $this->installMysql()
            ) {
                $this->initMysqlFile();
            } else {
                $this->index();
            }
        }
    }

    private function installMysql() {
        $mysqli = new mysqli(
                $this->host, $this->user, $this->password, NULL, $this->port);
        $db = $mysqli->escape_string($this->database);
        $table = $mysqli->escape_string($this->prefix . "plugin_variables");
        $pluginUser = $mysqli->escape_string($this->pluginUser);
        $pluginPass = $mysqli->escape_string($this->pluginPass);
        $host = $this->host;
        $query = "CREATE DATABASE IF NOT EXISTS `$db`;"
                . "use `" . $this->database . "`;"
                . "CREATE TABLE IF NOT EXISTS `$table` ("
                . "`name` VARCHAR(32) NOT NULL, "
                . "`value` TEXT NOT NULL, "
                . "PRIMARY KEY(`name`)); "
                . "GRANT INSERT, SELECT, DELETE, UPDATE "
                . "ON $db.$table TO '$pluginUser'@'$host' IDENTIFIED BY "
                . "'$pluginPass';";

        if ($mysqli->multi_query($query)) {
            return true;
        }
        return false;
    }

    private function checkConnection() {
        $level = error_reporting();
        error_reporting(0);
        $link = mysqli_connect(
                $this->host, $this->user, $this->password, NULL, $this->port
        );
        error_reporting($level);
        if (($errnr = mysqli_connect_errno())) {
            if ($errnr == 1045) {
                $this->errors['password'] = "Invalid username and password comb"
                        . "ination. MySQL Error: " . mysqli_connect_error();
            } else {
                $this->errors['mysql'] = mysqli_connect_error();
            }
            return false;
        } elseif (mysqli_ping($link)) {
            return true;
        }
        $this->errors['mysql'] = mysqli_error($link);
        return false;
    }

}
