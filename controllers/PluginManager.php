<?php
requirePlugin("head");
requirePlugin("tag");
requirePlugin("formtag");
requirePlugin("pllugin");
class PluginManager extends Controller {
    public function __construct() {
        parent::__construct();
        Head::add("http://code.jquery.com/jquery-2.1.1.min.js");
        echo "<head>" . Head::output(false) . "</head>";
        ?>"<head>"<?php £("Form"); ?> HELLO <?php £("/Form");
    }
}