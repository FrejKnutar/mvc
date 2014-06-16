<?php
class Form extends Tag
{
    public function __construct() {
        parent::__construct();
        
    }
    
    public function doStartTag() {
        echo "&ltForm&gt";
    }
    
    public function doEndTag() {
        echo "&lt/Form&gt";
    }
}
