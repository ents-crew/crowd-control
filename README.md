# Crowd Control
Crowd Control is a web-based lighting control panel. Users queue with the console in "rehearsal mode" where they can practice using the controls, then have a period of time where the controls are "live" and sending real lighting commands.

The commands are sent to a RabbitMQ server, and can then be consumed by other software (such as [ents-crew/interactive-dmx-merger](https://github.com/ents-crew/interactive-dmx-merger)) to control lights (either in the real world or a visualiser).

## Installation
You'll need a web server which can handle PHP, and `composer` to install dependencies.
1. Run `composer install` in your project directory.
2. Copy `config.php.template` to `config.php`.
3. Copy `queue.db.template` to `queue.db`.
4. Copy `resources/fixtures.js.template` to `resources/fixtures.js`.
5. Fill in the details in `config.php`.
6. Configure your fixtures in `resources/fixtures.js`.