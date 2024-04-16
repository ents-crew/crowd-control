<?php
  $config = require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Crowd Control - Ents Crew</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">

  <!-- Social sharing -->
  <meta content="summary_large_image" name="twitter:card">
  <meta content="@ustaents" name="twitter:site">
  <meta content="Crowd Control by Ents Crew" name="twitter:title">
  <meta content="Control the lights in Club 601 and watch your designs in real-time." name="twitter:description">
  <!--  <meta name="twitter:image" content="{{ base_url }}/assets/{{ meta.image }}">-->

  <!--  <meta property="og:url" content="" />-->
  <meta content="Crowd Control by Ents Crew" property="og:title"/>
  <meta content="Control the lights in Club 601 and watch your designs in real-time." property="og:description"/>
  <!--  <meta property="og:image" content="{{ base_url }}/assets/{{ meta.image }}" />-->

  <!-- Favicons -->
  <link href="/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180">
  <link href="/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
  <link href="/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
  <link href="/site.webmanifest" rel="manifest">
  <link color="#000000" href="/safari-pinned-tab.svg" rel="mask-icon">
  <meta content="Crowd Control" name="apple-mobile-web-app-title">
  <meta content="Crowd Control" name="application-name">
  <meta content="#2b5797" name="msapplication-TileColor">
  <meta content="#000000" name="theme-color">

  <link crossorigin="anonymous"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg=="
        rel="stylesheet"/>
  <link href='https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&amp;family=Montserrat&amp;display=swap'
        rel='stylesheet'>
  <link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.2.1/simplebar.min.css"
        integrity="sha512-uZTwaYYhJLFXaXYm1jdNiH6JZ1wLCTVnarJza7iZ1OKQmvi6prtk85NMvicoSobylP5K4FCdGEc4vk1AYT8b9Q=="
        rel="stylesheet"/>
  <link href="resources/style.css" rel="stylesheet">
</head>
<body>
<!-- Off air modal -->
<div aria-hidden="true" class="modal fade" data-backdrop="static" data-keyboard="false" id="off-air-modal"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-md-4 col-5">
            <img alt="Ents Crew" class="w-100" src="assets/logo.png"/>
          </div>
          <div class="col-md-7">
            <h3 class="modal-title">Crowd Control is offline.</h3>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <p><?= $config['offline_text'] ?></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" href="https://entscrew.net/join">Join Ents Crew</a>
      </div>
    </div>
  </div>
</div>

<!-- Intro modal -->
<div aria-hidden="true" class="modal fade" data-backdrop="static" data-keyboard="false" id="intro-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-md-4 col-5">
            <img alt="Ents Crew" class="w-100" src="assets/logo.png"/>
          </div>
          <div class="col-md-7">
            <h3 class="modal-title">Welcome to Crowd Control.</h3>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <p><?= $config['live_text'] ?></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary join-queue" data-dismiss="modal" type="button">Join the queue</button>
      </div>
    </div>
  </div>
</div>

<!-- Live mode intro -->
<div aria-hidden="true" class="modal fade" id="live-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title"><span class="live">Live mode</span> activated.</h3>
      </div>
      <div class="modal-body">
        <p><?= $config['live_mode_text'] ?></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" type="button">Okay!</button>
      </div>
    </div>
  </div>
</div>

<!-- Time up -->
<div aria-hidden="true" class="modal fade" data-backdrop="static" data-keyboard="false" id="end-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-md-4 col-5">
            <img alt="Ents Crew" class="w-100" src="assets/logo.png"/>
          </div>
          <div class="col-md-7">
            <h3 class="modal-title">Time up!</h3>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <?= $config['time_up_text'] ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary join-queue" data-dismiss="modal" type="button">Re-join the queue</button>
        <a class="btn btn-primary" href="https://entscrew.net/join">Join Ents Crew</a>
      </div>
    </div>
  </div>
</div>

<!-- Status bar -->
<div class="status">
  <div class="container status-content" id="status">
    Waiting for queue information...
  </div>
  <span aria-live="assertive" class="sr-only" id="status-screenreader">Waiting for queue information...</span>
</div>

<!-- Header -->
<div class="container">
  <header>
    <div class="row title align-items-center">
      <div class="col-md-2 col-5 pb-2">
        <img alt="Ents Crew" class="w-100" src="assets/logo.png"/>
      </div>
      <div class="col-md-10">
        <h1>Crowd Control</h1>
        <p><?= $config['strapline_text'] ?></p>
      </div>
    </div>
  </header>
  <p><?= $config['heading_text'] ?></p>
  <hr>
</div>

<?php 
echo $config['livestream_url'];
  // Only show the livestream if the url is set
  if ($config['livestream_url'] != null) {
?>
<!-- Live views section -->
<div class="container mt-4 live-views">
  <div class="embed-responsive embed-responsive-16by9">
    <!-- Embedded venue live stream -->
    <iframe allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen class="embed-responsive-item"
            src="<?= $config['livestream_url'] ?>"></iframe>
  </div>
  <small>Video hosted by YouTube and accessible under the <a href="https://policies.google.com/privacy" target="_blank">Google
    Privacy Policy</a>.</small>
</div>
<?php } ?>

<!-- Light groups carousel - populated by Javascript -->
<div class="container">
  <fieldset>
    <legend><h2 class="fixtures-title">Light groups</h2></legend>
    <div data-simplebar data-simplebar-auto-hide="false">
      <div class="fixtures-carousel btn-group-toggle" data-toggle="buttons" id="fixtures-carousel">
      </div>
    </div>
  </fieldset>
</div>

<!-- Fixture controls - populated by Javascript once a fixture is chosen from the carousel -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-10">
      <h3>Intensity</h3>
      <div id="intensity">
        <p>Choose a group of lights above to see available controls...</p>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-4 col-10">
      <fieldset>
        <legend><h3>Colour</h3></legend>
        <div class="colours btn-group-toggle" data-toggle="buttons" id="colour">
          <p>Choose a group of lights above to see available controls...</p>
        </div>
      </fieldset>
    </div>
    <div class="col-md-3 col-10">
      <fieldset>
        <legend><h3>Position</h3></legend>
        <div class="stacked-buttons btn-group-toggle" data-toggle="buttons" id="position">
          <p>Choose a group of lights above to see available controls...</p>
        </div>
      </fieldset>
    </div>
    <div class="col-md-3 col-10">
      <fieldset>
        <legend><h3>Effect</h3></legend>
        <div class="stacked-buttons btn-group-toggle" data-toggle="buttons" id="effect">
          <p>Choose a group of lights above to see available controls...</p>
        </div>
      </fieldset>
    </div>
  </div>
</div>

<script crossorigin="anonymous"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script crossorigin="anonymous"
        integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
        src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script crossorigin="anonymous"
        integrity="sha512-M5KW3ztuIICmVIhjSqXe01oV2bpe248gOxqmlcYrEzAvws7Pw3z6BK0iGbrwvdrUQUhi3eXgtxp5I8PDo9YfjQ=="
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script crossorigin="anonymous"
        integrity="sha512-jBhCdCCdbZ8l7FrZg15L1dUTgcAfnR1HXBcvU4LBUp9/T17ktq3hRjzY1t9h6RNJRjzDQyo9fmoehfDR1kqApw=="
        src="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.2.1/simplebar.min.js"></script>

<script src="resources/queue.js"></script>
<script src="resources/fixtures.js"></script>
<script src="resources/controls.js"></script>

</body>
</html>
