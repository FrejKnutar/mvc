<?php
Loader::loadPlugin("spotify");
class PluginManager extends Controller {
    
    private $viewDir = "pluginmanager";
    private $plugins = array();
    
    public function __construct() {
        parent::__construct();
        $this->spotify = new Spotify();
        $this->isAjax = filter_input(INPUT_GET, 'ajax') !== NULL;
    }
    
    public function index() {
        $this->loadView(
            $this->viewDir . "/home"
        );
    }
    
    public function test() {
        echo $this->isAjax ? 'AJAX' : 'NO AJAX';
    }
    
}