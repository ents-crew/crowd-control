<?php
// A request to this page will return an integer response:
/// -1000 - something has gone wrong, error has been logged.
/// -1 - your turn has expired.
/// 0 - it's your turn.
/// 1 or greater - your position in the queue, where 0 is your turn.
// On the server, you can require this script file and call the function checkQueue() to get the same result.

session_start();

// output the result of checkQueue if this script is directly requested
echo checkQueue();

function checkQueue() {
    $config = require 'config.php';

    // note the session ID
    $sessionID = session_id();

    // connect to database
    $db = new SQLite3($config["queueSQLite"]);

    // Record in the database the time of this request
    $noteHeartbeat = $db->prepare("UPDATE queue SET heartbeat_received = strftime('%s','now') WHERE id = ?");
    $noteHeartbeat->bindParam(1, $sessionID);
    if (!($noteHeartbeat->execute())) {
        error_log("Failed to record heartbeat to the database for user " . $sessionID . ". Error: " . $db->lastErrorMsg());
    }

    // delete anybody whose time at the front of the queue is up, or who hasn't sent a heartbeat in the last 30 seconds
    $db->exec("DELETE FROM queue WHERE eject < strftime('%s','now') OR (heartbeat_received < (strftime('%s','now') - 30));");

    // If person is in queue
    if (!empty($_SESSION["inQueue"])) {
        // Get the ID of the person with the lowest position in the queue, which will be the person who has been there longest
        $minID = $db->query("SELECT id FROM queue ORDER BY `position` ASC LIMIT 1")->fetchArray();

        // If there are results
        if (is_array($minID)) {
            // Set minID to the ID of the person first in line
            $minID = $minID[0];
        } else {
            // Null the ID, since the queue is empty
            $minID = null;
        }

        // if the ID of the person who's first in the queue is the same as the ID of the requester, let them in!
        if ($minID === $sessionID) {
            // Get the expiry time of the person who's first in queue
            $minIDEjectTime = $db->query("SELECT eject FROM queue ORDER BY `position` ASC LIMIT 1")->fetchArray()[0];

            // if there is no eject time, add one
            if (empty($minIDEjectTime)) {
                // Prepare statement to add an expiration time to the person at the front of the queue
                // Time defined in config file
                $addExpiration = $db->prepare("UPDATE queue SET eject = (strftime('%s','now') + ?) WHERE id = ?;");
                $addExpiration->bindParam(1, $config["liveDuration"]);
                $addExpiration->bindParam(2, $sessionID);

                // Add the expiration
                if ($addExpiration->execute()) { // if it was successfully added
                    // mark in the session that the person is at the front of the queue
                    $_SESSION["atFront"] = true;

                    // Send 0 back to the client so it updates to/stays in live mode
                    return 0;
                } else {
                    error_log("Failed to add expiration time to the database for user " . $sessionID . ". Error: " . $db->lastErrorMsg());
                }
            } else {
                // mark in the session that the person is at the front of the queue
                $_SESSION["atFront"] = true;

                // Send 0 back to the client so it updates to/stays in live mode
                return 0;
            }

        } else { // if person is not at the front of the queue

            // if they were at the front but their session expired so they are no longer at the front
            if ($_SESSION["atFront"]) {
                // clear their session
                session_destroy();

                // return -1 to tell them their session has expired
                return -1;
            } else {
                // return queue position to put/keep them in rehearsal mode
                return getQueuePosition($db, $sessionID);
            }
        }
    } else { // If person is not yet in the queue
        // Prepare statement to add them to queue
        $addToQueue = $db->prepare("INSERT INTO queue (id, heartbeat_received) VALUES (?, strftime('%s','now'));");
        $addToQueue->bindParam(1, $sessionID);

        // Add them to queue
        if ($addToQueue->execute()) { // if they were successfully added
            // update session variable to reflect that they're in the queue
            $_SESSION["inQueue"] = true;
            // mark in the session that the person is not at the front of the queue
            $_SESSION["atFront"] = false;

            // return queue position to put/keep them in rehearsal mode
            return getQueuePosition($db, $sessionID);
        } else {
            error_log("Failed to add user " . $sessionID . " to the queue. Error: " . $db->lastErrorMsg());
        }
    }

    return -1000;
}

function getQueuePosition($db, $sessionID) {
    // prepare statement to get the current user's queue position
    $getPosition = $db->prepare("SELECT (SELECT `position` FROM queue WHERE id = ?) - min(`position`) FROM queue;");
    $getPosition->bindParam(1, $sessionID);

    // return their position
    return $getPosition->execute()->fetchArray()[0];
}