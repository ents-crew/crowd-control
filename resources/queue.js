var queuePosition = "";

// Function to check user's position in the queue - it will also add them to the queue if they're not already in it
function checkQueuePosition() {
    $.get("checkQueue.php", function (position) {
        // Convert the position to a number
        position = Number(position);

        if (position === 0) {
            $("#status").html(
                '<strong class="live">Live mode</strong> Your controls are currently affecting what you see on the live stream!')
        } else if (position === 1) {
            $("#status").html(
                "<strong>Rehearsal mode</strong> - your controls aren't active so your designs won't appear live, but you can " +
                "practise. You're <strong>next</strong> in the queue.");
        } else {
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
    $("#joinQueue").on("click", function () {
        // initial queue position check
        checkQueuePosition();
        // check queue position every 5 seconds
        setInterval(checkQueuePosition, 5000)
    });
});



