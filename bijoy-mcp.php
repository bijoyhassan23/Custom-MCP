<?php
require_once __DIR__ . '/log.php';

header('Access-Control-Allow-Origin: *');

$request = json_decode(file_get_contents('php://input'), true);

log_message("Received request: " . json_encode($request));