<?php
function mysqliconnection() {
    $trace = debug_backtrace();
    $folder = strstr(
        str_remove_first(
            filter_input(INPUT_SERVER,"SERVER_ROOT"),dirname($trace[0]['file'])
        ),
        DIRECTORY_SEPARATOR,
        true
    );
    if ($folder == PLUGIN_FOLDER) {
        $user = PLUGIN_USER;
        $password = "";
    } else {
        $user = "$user";
        $password = "$password";
    }
    return new mysqli("localhost", $user, $password, "mvc", 80);
}