<?php
function mysqliconnection() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "mvc";
    $port = 80;
    return new mysqli($host, $user, $password, $database, $port);
}