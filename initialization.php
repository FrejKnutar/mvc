<?php
// The URL to the web server
define(
    "HOME",
    'http'  . (filter_input(INPUT_SERVER, "HTTPS") == "on" ? "s" : '') . "://" .
    filter_input(INPUT_SERVER, "SERVER_NAME") .
    (filter_input(INPUT_SERVER, "SERVER_PORT") != 80 ? filter_input(INPUT_SERVER, "SERVER_PORT") : '')
);
define("DEFAULT_CLASS", "PluginManager");
define("DEFAULT_METHOD", "index");