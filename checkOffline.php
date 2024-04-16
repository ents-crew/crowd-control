<?php
// A request to this page will return 1 if the system is offline.
// Otherwise, it will return 0.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$config = require 'config.php';

if ($config["offline"]) {
    echo 1;
} else {
    echo 0;
}