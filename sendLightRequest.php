<?php

require "checkQueue.php";
require_once __DIR__ . '/vendor/autoload.php';

$queuePosition = checkQueue();

echo $queuePosition;

// If it's their turn, so proceed with request
if ($queuePosition === 0 || $queuePosition === -2 | $queuePosition === -3) {
    $config = require 'config.php';

    // connect to database
    $db = require 'db.php';

    // note the session ID
    $sessionID = session_id();

    // Record in the database the time of this request
    $noteHeartbeat = $db->prepare("UPDATE queue SET command_received = strftime('%s','now') WHERE id = ?");
    $noteHeartbeat->bindParam(1, $sessionID);
    if (!($noteHeartbeat->execute())) {
        error_log("Failed to record command heartbeat to the database for user " . $sessionID . ". Error: " . $db->lastErrorMsg());
    }

    // Iterate through the passed data and remove anything obviously malicious
    foreach ($_POST as $item) {
        $item = clearUpInput($item);
    }

    // Add command to the database "queue"
    $command = $_POST["fixture"] . "." . $_POST["attribute"] . "." . $_POST["action"];
    $addCommand = $db->prepare("INSERT INTO commands (command) VALUES (?)");
    $addCommand->bindParam(1, $command);
    if (!($addCommand->execute())) {
        error_log("Failed to add command to the database for user " . $sessionID . ". Error: " . $db->lastErrorMsg());
    }

} else { // if it's not the player's turn, respond and say Unauthorised
    http_response_code(401);
}

// Tidy and secure user input
function clearUpInput($data) {
    // Remove unnecessary characters
    $data = trim($data);
    // Replace forward slashes with code
    $data = stripslashes($data);
    // Replace angle brackets and HTML characters with code
    $data = htmlspecialchars($data, ENT_QUOTES);
    // Add slashes to make sure can reach a database properly
    $data = addslashes($data);
    // Send the data back to the program
    return $data;
}