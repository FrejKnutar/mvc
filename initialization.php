<?php

define("CONTROLLER_DIR", "controllers");
define("DEFAULT_CONTROLLER", "PluginManager");
define("DEFAULT_METHOD", "index");
define("MODEL_DIR", "models");
define("PLUGIN_DIR", "plugins");
define("TABLE_PREFIX", "mvc_");
define("VIEW_DIR", "views");
define("PLUGIN_TABLE", "plugin_table");
/**
 * Replace the first occurrencee of the search string with the replacement 
 * string.
 * @param string $search <p>
 * The value being searched for, otherwise known as the needle.
 * </p>
 * @param string $replace <p>
 * The replacement value that replaces found <i>search</i> values.
 * </p>
 * @param string $subject <p>
 * The string being searched and replaced on, otherwise known as the haystack.
 * </p>
 * </p>
 * @return string This function returns the string with the replaced value.
 */
function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

/**
 * Removes the first occurence of the search string.
 * @param string $haystack The string being searched and removed from.
 * @param string $needle The value being searched from.
 * @return string This function returnes the string the removed value.
 */
function str_remove_first($haystack, $needle) {
    return str_replace_first($needle, '', $haystack);
}

/**
 * Validates if an instance of a class can be created and if a method of that
 * class can be called.
 * @param String $class  The name of the class that is to be checked
 * @param String $method <p>The name of the method that is to be called</p><p>If
 *                       <b>NULL</b> is given the constructor of the class is
 *                       selected as a method.</p>
 * @param int $argc      <p>The number of parameters that are to be sent to the
 *                       method</p>
 * @return boolean       <b>true</b> if an instance of the class can be created
 *                       and if the method can be called with the set number of
 *                       parameters, else <b>false</b>
 */
function is_instantiable($class, $method = "__construct", $argc = 0) {
    if (!class_exists($class)) {
        return false;
    }
    $refClass = new ReflectionClass($class);
    if (!$refClass->isInstantiable()) {
        return false;
    }
    $refConst = $refClass->getConstructor();
    if (!$refConst->isPublic()) {
        return false;
    }
    if ($method == "__construct") {
        return $refConst->getNumberOfRequiredParameters() <= $argc && $refConst->getNumberOfParameters() >= $argc;
    } elseif ($refConst->getNumberOfRequiredParameters() == 0 && $refClass->hasMethod($method)) {
        $refMet = $refClass->getMethod($method);
        return $refMet->isPublic() &&
                $refMet->getNumberOfRequiredParameters() <= $argc &&
                $refMet->getNumberOfParameters() >= $argc;
    }
    return false;
}

if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);              // Replace every part of the first URL when there's one of the second URL
    define('HTTP_URL_JOIN_PATH', 2);            // Join relative paths
    define('HTTP_URL_JOIN_QUERY', 4);           // Join query strings
    define('HTTP_URL_STRIP_USER', 8);           // Strip any user authentication information
    define('HTTP_URL_STRIP_PASS', 16);          // Strip any password authentication information
    define('HTTP_URL_STRIP_AUTH', 32);          // Strip any authentication information
    define('HTTP_URL_STRIP_PORT', 64);          // Strip explicit port numbers
    define('HTTP_URL_STRIP_PATH', 128);         // Strip complete path
    define('HTTP_URL_STRIP_QUERY', 256);        // Strip query string
    define('HTTP_URL_STRIP_FRAGMENT', 512);     // Strip any fragments (#identifier)
    define('HTTP_URL_STRIP_ALL', 1024);         // Strip anything but scheme and host

    /**
     * <p>Build an URL</p><p>The parts of the second URL will be merged into the
     *  first according to the flags argument.</p>
     * @param mixed $url (Part(s) of) an URL in form of a string or 
     *                   associative array like parse_url() returns
     * @param mixed $parts Same as the first argument
     * @param int $flags A bitmask of binary or'ed HTTP_URL constants 
     *                   (Optional); <b>HTTP_URL_REPLACE</b> is the default
     * @param array $new_url If set, it will be filled with the parts of the 
     *                       composed url like parse_url() would return 
     * @return mixed Returns the new URL as string on success or <b>FALSE</b> on
     *                       failure.
     */
    function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false) {
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        } else if ($flags & HTTP_URL_STRIP_AUTH) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
        }
        $parse_url = parse_url($url);
        if (isset($parts['scheme'])) {
            $parse_url['scheme'] = $parts['scheme'];
        }
        if (isset($parts['host'])) {
            $parse_url['host'] = $parts['host'];
        }
        if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key])) {
                    $parse_url[$key] = $parts[$key];
                }
            }
        } else {
            if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
                if (isset($parse_url['path'])) {
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                } else {
                    $parse_url['path'] = $parts['path'];
                }
            }
            if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
                if (isset($parse_url['query'])) {
                    $parse_url['query'] .= '&' . $parts['query'];
                } else {
                    $parse_url['query'] = $parts['query'];
                }
            }
        }
        foreach ($keys as $key) {
            if ($flags & (int) constant('HTTP_URL_STRIP_' . strtoupper($key))) {
                unset($parse_url[$key]);
            }
        }
        $new_url = $parse_url;
        return
                ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
                . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
                . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
                . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
                . ((isset($parse_url['path'])) ? $parse_url['path'] : '')
                . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
                . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
        ;
    }

}

/**
 * Returns the URL to the base of the MVC with the path appended to it if one
 * is given. An assiciative array can be given to create a GET query.
 * @param String $info     A string starting with a slash '/'. If the string 
 *                         does not start with a slash one is prepended to the 
 *                         string.
 * @param array $query     An assiciative array containing the GET variables 
 *                         that are to be appended to the URL.
 * @param String $fragment An anchor tag that is to be appended to the URL.
 * @return type The URL to the base of the MVC with the suffix and GET variables
 *              appended to it.
 */
function get_url($info = '', array $query = array(), $fragment = '') {
    $parts = array();
    $parts['scheme'] = 'http' . (filter_input(INPUT_SERVER, "HTTPS") == "on" ? 's' : '');
    $parts['host'] = filter_input(INPUT_SERVER, "SERVER_NAME");
    if (filter_input(INPUT_SERVER, "SERVER_PORT") != 80) {
        $parts['port'] = filter_input(INPUT_SERVER, "SERVER_PORT");
    }
    $parts['path'] = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
    if (strlen($info) > 0) {
        $parts['path'] .= ($info{0} == '/' ? '' : '/') . $info;
    }
    if (!empty($query)) {
        $parts['query'] = http_build_query($query);
    }
    if (strlen($fragment) > 0) {
        $parts['fragment'] = $fragment;
    }
    return http_build_url('', $parts);
}

/**
 * Generates the URL to the current path of the folder of the calling file, 
 * using the mvc folder as the root folder.
 * @param String $info     The suffix that is to be appended to the path of the 
 *                         URL.
 * @param array $query     The assiciative array containing the GET variables that are
 *                         to create the URL query.
 * @param String $fragment The fragment, or anchor.
 * @return string      The URL to the location on the file system.
 */
function get_cur_url($info, array $query = array(), $fragment = '') {
    $trace = debug_backtrace();
    if (DIRECTORY_SEPARATOR == '/') {
        $path = str_remove_first(
                dirname($trace[0]['file']), dirname(filter_input(INPUT_SERVER, "SCRIPT_FILENAME"))
        );
    } else {
        $path = str_remove_first(
                str_replace(
                        '\\', '/', dirname($trace[0]['file'])
                ), dirname(filter_input(INPUT_SERVER, "SCRIPT_FILENAME"))
        );
    }
    return get_url($path . ($info{0} == '/' ? '' : '/') . $info, $query, $fragment);
}
