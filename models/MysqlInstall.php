<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MysqlInstall {
    private $connection = null;
    
    public function __construct() {
        $this->connection = mysqlconnection("rw");
    }
    
    public function createPluginUser() {
        
    }
    
}