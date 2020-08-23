var queueChecker;
var isLive = false;

// Function to check user's position in the queue - it will also add them to the queue if they're not already in it
function checkQueuePosition() {
    $.get("checkQueue.php", function (position) {
        // Convert the position to a number
        position = Number(position);

        // if the queue info is invalid
        if (isNaN(position)) {
            $("#status").text("Problem getting queue information - trying again...");
        } else if (position === 0) { // if the person is first
            // if they were not already live
            if (!isLive) {
                // mark them as now live
                isLive = true;
                // alert user that they're live
                $("#live-modal").modal();
                // send the user's control choices to the server - this way, their edits in rehearsal mode appear immediately
                postAllControls();
            }
            // update status bar
            $("#status").html(
                '<strong class="live">Live mode</strong> Your controls are currently affecting what you see on the live stream!')
        } else if (position === 1) { //  if the person is next
            // remember they're not live
            isLive = false;
            // update status bar
            $("#status").html(
                "<strong>Rehearsal mode</strong> - your controls aren't active so your designs won't appear live, but you can " +
                "practise. You're <strong>next</strong> in the queue.");
        } else if (position === -1) { // if the person's turn has just ended
            // remember they're not live
            isLive = false;
            // Stop checking queue position
            clearInterval(queueChecker);
            // Clear status bar
            $("#status").text("Waiting for queue information...");
            // Alert the user
            $("#end-modal").modal();
        } else if (position === -2) { //  if the person's turn will expire soon
            // remember they're live
            isLive = true;
            // update status bar
            $("#status").html(
                "<strong class='live'>Live mode</strong> Your session is almost up - time for some final tweaks!");
            $("#status").effect("shake");
        } else if (position === -3) { //  if the person's hasn't made any changes recently
            // remember they're live
            isLive = true;
            // update status bar
            $("#status").html(
                "<strong class='live' id='live-status'>Live mode</strong> Keep making changes, or your session will be cut short!");
            $("#status").effect("shake");
        } else { // if the person is anywhere else in the queue
            // remember they're not live
            isLive = false;
            // update status bar
            $("#status").html(
                "<strong>Rehearsal mode</strong> - your controls aren't active so your designs won't appear live, but you can " +
                "practise. You're number " + position + " in the queue.");
        }
    })
}

// Function to iterate through all fixtures and send their active state to the server
function postAllControls() {
    // for each fixture
    fixtureKeys.forEach(function (fixture) {
        // for each attribute
        Object.keys(fixtures[fixture]["active"]).forEach(function (attribute) {
            // POST its current value to the server
            $.post("sendLightRequest.php",
                {
                    fixture: fixture,
                    attribute: attribute,
                    action: fixtures[fixture]["active"][attribute]
                })
        })
    })
}

// Prepare the page!
$(document).ready(function () {
    // Check if the system is offline
    $.get("checkOffline.php", function (offline) {
        // Convert status to a number
        offline = Number(offline);

        if (offline === 0) {
            // Show intro modal
            $("#intro-modal").modal();

            // when user clicks to join the queue
            $(document).on("click", ".join-queue", function () {
                // initial queue position check
                checkQueuePosition();
                // check queue position every 5 seconds
                queueChecker = setInterval(checkQueuePosition, 5000)
            });
        } else {
            $("#off-air-modal").modal();
        }
    });
});



