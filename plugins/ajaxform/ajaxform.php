<?php
requirePlugin("head");
class Form
{
    private $inputs = array();
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function add($type, $name, $defaultValue = "") {
        $this->inputs[$name] = array($type, $defaultValue);
    }
}
