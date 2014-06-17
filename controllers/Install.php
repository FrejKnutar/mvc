<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Install extends Controller {
    private $mysqlFilePath;
    public function __construct() {
        $this->mysqlFilePath = self::$root . DIRECTORY_SEPARATOR."mysqlconnector.php";
        if (!file_exists($this->mysqlFilePath)) {
            touch($this->mysqlFilePath);
        }
    }
    
    public function index() {
        $nav = array("MySQL install");
        $this->loadView(
            "install", 
            array(
                "nav" => $nav,
                "action" => HOME . '/' . get_class($this) . '/mysqlPost'
            )
        );
    }
    
    private function initMysqlFile(
            $host, $user, $password, $database, $port, $pluginUser, $pluginPass)
    {
        $contents = "<?php" . PHP_EOL . "function mysqliconnection() {"
                . PHP_EOL . '\t$trace = debug_backtrace();' . PHP_EOL . '\t$fol'
                . 'der = strstr(' . PHP_EOL . '\t\tstr_replace(' .PHP_EOL . '\t'
                . '\t\tfilter_input(INPUT_SERVER, "SERVER_ROOT"),' . PHP_EOL
                . "\t\t\t''," . PHP_EOL . '\t\t\tdirname($trace[0]["file"],1)' 
                . PHP_EOL . '\t\t),' . PHP_EOL . '\t\tDIRECTORY_SEPARATOR,'
                . PHP_EOL . '\t\ttrue' . PHP_EOL . '\t);' . PHP_EOL . '\tif ($f'
                . 'older == PLUGIN_FOLDER) {' . PHP_EOL . '\t\t$user = "'
                . $pluginUser . '";' . PHP_EOL . '\t\t$password = "'
                . $pluginPass . '";' . PHP_EOL . '\t} else {' . PHP_EOL . '\t\t'
                . '$user = "' . $user . '";' . PHP_EOL . '\t\t$password = "'
                . $password . '";' . PHP_EOL . '\t}' . PHP_EOL . '\treturn new '
                . 'mysqli("' . $host . '", $user, $password, "' . $database
                . '", ' . $port . ');' . PHP_EOL . '}';
        file_put_contents($this->mysqlFilePath, $contents);
    }
    
    public function mysqlPost() {
        $error = array();
        $host       = filter_input(INPUT_POST, 'host'      ) | "";
        $user       = filter_input(INPUT_POST, 'user'      ) | "";
        $password   = filter_input(INPUT_POST, 'password'  ) | "";
        $database   = filter_input(INPUT_POST, 'database'  ) | "";
        $prefix     = filter_input(INPUT_POST, 'prefix'    ) | "";
        $port       = filter_input(INPUT_POST, 'port'      ) | "";
        $pluginUser = filter_input(INPUT_POST, 'pluginuser') | "";
        $pluginPass = filter_input(INPUT_POST, 'pluginpass') | substr(md5(rand()), 0, 15);
        $this->checkConnection($host, $user, $password, $database, $port);
        $this->initMysqlFile(
            $host, $user, $password, $database, $port, $pluginUser, $pluginPass
        );
    }
    private function checkConnection($host, $user, $password, $database, $port) {
        $mysqli = new mysqli($host, $user, $password, NULL, $port);
    }
}