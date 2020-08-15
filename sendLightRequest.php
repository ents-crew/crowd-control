<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require "checkQueue.php";
require_once __DIR__ . '/vendor/autoload.php';

// If the player's queue position is 0, it's their turn, so proceed with request
if (checkQueue() === 0) {
    $config = require 'config.php';

    // connect to database
    $db = new SQLite3($config["queueSQLite"]);

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

    // connect to RabbitMQ
    $connection = new AMQPStreamConnection($config["rabbitHost"], $config["rabbitPort"], $config["rabbitUser"], $config["rabbitPassword"]);
    $channel = $connection->channel();

    // pick the queue
    $channel->queue_declare($config["rabbitQueueName"], $config["rabbitQueuePassive"], $config["rabbitQueueDurable"], $config["rabbitQueueExclusive"], $config["rabbitQueueAutoDelete"]);

    // prepare the message
    $msg = new AMQPMessage($_POST["fixture"] . "." . $_POST["attribute"] . "." . $_POST["action"]);

    // send!
    $channel->basic_publish($msg, $config["rabbitExchange"], $config["rabbitRoutingKey"]);
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