<?php
abstract class Head
{
    /**
     * The headers that are to be included in the  HTML file
     * @var type Array
     */
    private static $includes = array();
    private static $extensions = array();
    private static $charset = NULL;
    private static $meta = array();
    private static $httpEquiv = array();
    
    static function addExtension($ext, $closure) {
        self::$extensions[$ext] = $closure;
    }
    
    static function add($fileName, $attributes = array()) {
        if (!filter_var($fileName, FILTER_VALIDATE_URL)) {
            $trace = debug_backtrace();
            $path = str_replace(
                filter_input(INPUT_SERVER, "SERVER_ROOT"),
                '',
                dirname($trace[0]['file'])
            );
            if (DIRECTORY_SEPARATOR == '\\') {
                $path = str_replace('\\', '/', $path);
                $fileName = str_replace('\\', '/', $fileName);
            }
            if (substr($path, -1) != '/') {
                $path .= '/';
            }
            $path = HOME . '/' . $path . $fileName;
            self::$includes[$path] = $attributes;
        } else {
            self::$includes[$fileName] = $attributes;
        }
    }
    
    static function meta($param1, $param2, $param3=NULL) {
        if(strtolower($param1) == "charset") {
            self::$charset = $param2;
        } elseif(strtolower($param1) == "http-equiv") {
            $name = $param2;
            $content = $param3;
            if (strtolower($param3) == "scheme") {
                self::$httpEquiv[$name] = array($content, $param3);
            } else {
                self::$httpEquiv[$name] = $content;
            }
        } else {
            $name = $param1;
            $content = $param2;
            if (strtolower($param3) == "scheme") {
                self::$meta[$name] = array($content, $param3);
            } else {
                self::$meta[$name] = $content;
            }
        }
    }
    
    static function output($echo = true) {
        $str = '';
        if (self::$charset != NULL) {
            $str .= '<charset=' . self::$charset . ">";
        }
        foreach (self::$httpEquiv as $key => $val) {
            $str .= "<meta http-equiv=\"$key\"";
            if (is_array($val)) {
                $str .= ' content="' . $val[0] .'" scheme="' . $val[1] . '">';
            } else {
                $str .= " content=\"$val\">";
            }
        }
        foreach (self::$meta as $name => $val) {
            $str .= "<meta name=\"$name\"";
            if (is_array($val)) {
                $str .= ' content="' . $val[0] .'" scheme="' . $val[1] . '">';
            } else {
                $str .= " content=\"$val\">";
            }
        }
        foreach (self::$includes as $key=>$val) {
            if (is_string(strstr($key, '?'))) {
                $ext = substr(strrchr(strstr($key, '?'), '.'), 1);
            } else {
                $ext = substr(strrchr($key, '.'), 1);
            }
            if (array_key_exists($ext, self::$extensions)) {
                $params = empty($val) ? array($key) : array($key, $val);
                $str .= call_user_func_array(self::$extensions[$ext], $params);
            }
        }
        if ($echo) {
            echo $str;
        }
        return $str;
    }
}
Head::addExtension(
    "js", 
    function($src, $arguments = array("type"=>"text/javascript")) {
        $str = "<script";
        foreach ($arguments as $name=>$value) {
            $str .= " $name=\"$value\"";
        }
        $str .= "src=\"$src\"></script>";
        return $str;
    }
);
Head::addExtension(
    "css", 
    function($href, $arguments = array("rel"=>"stylesheet", "type"=>"text/css")) {
        $str = "<link";
        foreach ($arguments as $name=>$value) {
            $str .= " $name=\"$value\"";
        }
        $str .= "href=\"$href\"";
        return $str;
    }
);
