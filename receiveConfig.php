<?php
    // Receive configuration data from the hosted bridge.

    if (count($_POST) > 0) {
        // values have been posted
        // TODO: some sort of authentication

        $db = new SQLite3("config.db");
        $config = require 'config.php';

        foreach ($_POST as $key => $value) {
            if ($key === "fixtures") {
                // change the fixture config on the server

                // write to the fixtures.js config file
                $configFile = fopen("resources/fixtures.js", "w") or die("Unable to open file!");
                fwrite($configFile, "const fixtures = " . $value . ";");
                fclose($configFile);

            } else {
                // some other general value to update in the database
                $stmt = $db->prepare("UPDATE config SET value = ? WHERE configId = ?");
                $stmt->bindParam(1, $value);
                $stmt->bindParam(2, $key);
                $stmt->execute();
            }
        }
    }

?>