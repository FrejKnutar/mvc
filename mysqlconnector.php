<?php
function mysqliconnection() {
    return new mysqli($host, $user, $password, $database, $port, $socket);
}