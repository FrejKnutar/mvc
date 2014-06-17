<?php
requirePlugin("head");
requirePlugin("plugin");
class PluginManager extends Controller {
    public function __construct() {
        Head::add("http://code.jquery.com/jquery-2.1.1.min.js");
        echo "<head>" . Head::output(false) . "</head>";
    }
}