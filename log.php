<?php
define('LOG_FILE', 'app.log');
function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND);
    return true;
}

function read_log() {
    if (file_exists(LOG_FILE)) {
        return file_get_contents(LOG_FILE);
    } else {
        return false;
    }
}