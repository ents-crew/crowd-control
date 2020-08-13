<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

session_start();
require "checkQueue.php";
require_once __DIR__ . '/vendor/autoload.php';

// If the player's queue position is 0, it's their turn, so proceed with request
if (checkQueue() === 0) {
    $config = require 'config.php';

    // Iterate through the passed data and remove anything obviously malicious
    foreach ($_POST as $item) {
        $item = clearUpInput($item);
    }

    // connect to RabbitMQ
    $connection = new AMQPStreamConnection($config["rabbitHost"], $config["rabbitPort"], $config["rabbitUser"], $config["rabbitPassword"]);
    $channel = $connection->channel();

    // pick the queue
    $channel->queue_declare('incoming', false, false, false, false);

    // prepare the message
    $msg = new AMQPMessage($_POST["fixture"] . "." . $_POST["attribute"] . "." . $_POST["action"]);

    // send!
    $channel->basic_publish($msg, 'incoming', 'command');
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