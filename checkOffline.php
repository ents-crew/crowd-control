<?php
// A request to this page will return 1 if the system is offline.
// Otherwise, it will return 0.

$config = require 'config.php';

if ($config["offline"]) {
    echo 1;
} else {
    echo 0;
}