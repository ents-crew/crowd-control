# Crowd Control
Crowd Control is a web-based lighting control panel. Users queue with the console in "rehearsal mode" where they can practice using the controls, then have a period of time where the controls are "live" and sending real lighting commands.

The commands are sent to a RabbitMQ server, and can then be consumed by other software to control lights (either in the real world or a visualiser).

## Installation
1. Copy `config.php.template` to `config.php`.
2. Copy `queue.db.template` to `queue.db`.
3. Fill in the details in `config.php`.