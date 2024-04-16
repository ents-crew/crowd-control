<?php
    $config = require 'config.php';
    return new SQLite3($config["queueSQLite"]);
?>