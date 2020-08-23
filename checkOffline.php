<?php
// A request to this page will return 1 if the system is offline.
// Otherwise, it will return 0.

$config = require 'config.php';

if ($config["offline"]) {
    return 1;
} else {
    return 0;
}