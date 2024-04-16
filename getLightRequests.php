<?php
    // This page will return a list of all the outstanding lighting commands.

    // TODO: make this somewhat authenticated from the backend

    echo json_encode(getCommands());

    // Get all of the outstanding commands from the database
    function getCommands() {
        $config = require 'config.php';

        $db = require 'db.php';

        $results = $db->query("SELECT * FROM commands;");

        $commands = array();
        $lastId = 0;
        while ($command = $results->fetchArray()) {
            $commands[] = $command["command"];
            $lastId = $command["id"];
        }

        // remove commands from db now they have been retrieved
        $db->query("DELETE FROM commands WHERE id <= $lastId;");

        return $commands;
    }
?>