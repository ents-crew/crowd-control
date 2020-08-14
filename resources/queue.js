var queueChecker;
var isLive = false;

// Function to check user's position in the queue - it will also add them to the queue if they're not already in it
function checkQueuePosition() {
    $.get("checkQueue.php", function (position) {
        // Convert the position to a number
        position = Number(position);

        // if the queue info is invalid
        if (isNaN(position)) {
            $("#status").text("Waiting for queue information... problem");
        } else if (position === 0) { // if the person is first
            // if they were not already live
            if (!isLive) {
                isLive = true;
                // alert user that they're live
                $("#live-modal").modal();
                // update status bar
                $("#status").html(
                    '<strong class="live">Live mode</strong> Your controls are currently affecting what you see on the live stream!')
            }
        } else if (position === 1) { //  if the person is next
            isLive = false;
            // update status bar
            $("#status").html(
                "<strong>Rehearsal mode</strong> - your controls aren't active so your designs won't appear live, but you can " +
                "practise. You're <strong>next</strong> in the queue.");
        } else if (position === -1) { // if the person's turn has just ended
            // Stop checking queue position
            clearInterval(queueChecker);
            // Clear status bar
            $("#status").text("Waiting for queue information...");
            // Alert the user
            $("#end-modal").modal();
        } else { // if the person is anywhere else in the queue
            isLive = false;
            // update status bar
            $("#status").html(
                "<strong>Rehearsal mode</strong> - your controls aren't active so your designs won't appear live, but you can " +
                "practise. You're number " + position + " in the queue.");
        }
    })
}

// Prepare the page!
$(document).ready(function () {
    // Show intro modal
    $("#intro-modal").modal();

    // when user clicks to join the queue
    $(document).on("click", ".join-queue", function () {
        // initial queue position check
        checkQueuePosition();
        // check queue position every 5 seconds
        queueChecker = setInterval(checkQueuePosition, 5000)
    });
});



