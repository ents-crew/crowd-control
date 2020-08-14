// Get the key for each fixture in a separate array
const fixtureKeys = Object.keys(fixtures);

// Returns the ID of the currently-active fixture
function getActiveFixture() {
    return $("input:checked[data-controls='fixtures']").data("interactiveid");
}

// Add a colour to the colour button section
function addColourButtons() {
    var activeFixture = getActiveFixture();

    // Get all valid colours of the currently active fixture
    var colours = Object.keys(fixtures[activeFixture].colour);

    // Iterate through all the colours
    colours.forEach(function (item) {
        // Empty variables to store the "active" status class and the input checked status
        var activeClass = "";
        var inputChecked = "";

        // if the item is selected, update the active class and checked input state
        if (fixtures[activeFixture].active.colour === item) {
            activeClass = "active";
            inputChecked = "checked";
        }

        // Append to the button list
        $("#colour").append(
            '<label class="btn radio-button ' + activeClass + '" style="background-color: #' + fixtures[activeFixture].colour[item][2] + '">\n' +
            '          <input name="colour" type="radio" class="control-button" data-controls="colour" data-interactiveid="' + item + '" ' + inputChecked + '>\n' +
            '          Red</label>'
        )
    });
}

// Add the intensity fader for the current light
function addIntensityFader() {
    var maxIntensity = fixtures[getActiveFixture()].maxIntensity;
    var currentIntensity = fixtures[getActiveFixture()].active.intensity;

    $("#intensity").append(
        '<input type="range" id="intensityFader" name="intensity" min="0" max="' + maxIntensity + '" value="' + currentIntensity + '" data-controls="intensity">')
}

// Add a colour to the colour button section, based on the keys
function addButtons(type) {
    var activeFixture = getActiveFixture();

    // Get all valid items of a given attribute of the currently active fixture
    var attributes = Object.keys(fixtures[activeFixture][type]);

    // Iterate through all the attributes
    attributes.forEach(function (item) {
        // Empty variables to store the "active" status class and the input checked status
        var activeClass = "";
        var inputChecked = "";

        // if the item is selected, update the active class and checked input state
        if (fixtures[activeFixture].active[type] === item) {
            activeClass = "active";
            inputChecked = "checked";
        }

        // Append to the button list
        $("#" + type).append(
            '<label class="btn radio-button ' + activeClass + '">\n' +
            '          <input name="' + type + '" type="radio" class="control-button" data-controls="' + type + '" data-interactiveid="' + item + '" ' + inputChecked + '>\n' +
            '          ' + fixtures[activeFixture][type][item][0] + '</label>'
        )
    });
}

// Prepare the page!
$(document).ready(function () {
    // Generate the buttons for all the fixtures
    fixtureKeys.forEach(function (item) {
        // Append to the carousel
        $("#fixtures-carousel").append(
            '<label class="btn radio-button">\n' +
            '      <input name="groups" type="radio" data-controls="fixtures" data-interactiveid="' + item + '">\n' +
            '      ' + fixtures[item].name + '' +
            '</label>'
        )
    });

    // When a fixture is chosen
    $("input[data-controls='fixtures']").on("change", function () {
        // Remove all controls
        $("#colour").empty();
        $("#position").empty();
        $("#effect").empty();
        $("#intensity").empty();

        // Generate new controls
        addColourButtons();
        addIntensityFader();
        addButtons("position");
        addButtons("effect");

        // Create a listener for when a control button is pressed
        $(".control-button").on("change", function () {
            // Update the currently active attribute in the fixtures object
            fixtures[getActiveFixture()]["active"][$(this).data("controls")] = $(this).data("interactiveid");

            // POST the information to the request handler
            $.post("sendLightRequest.php",
                {
                    fixture: getActiveFixture(),
                    attribute: $(this).data("controls"),
                    action: fixtures[getActiveFixture()][$(this).data("controls")][$(this).data("interactiveid")][1]
                })
        });

        // Create a listener for when intensity is changed
        $("#intensityFader").on("change", function () {
            // Update the currently active attribute in the fixtures object
            fixtures[getActiveFixture()]["active"]["intensity"] = $(this).val();

            // POST the information to the request handler
            $.post("sendLightRequest.php",
                {
                    fixture: getActiveFixture(),
                    attribute: "intensity",
                    action: $(this).val()
                })
        });
    });
});
