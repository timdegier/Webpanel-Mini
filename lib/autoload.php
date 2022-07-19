<?php

//Start session
session_start();

// Load config
require 'config/config.php';

// Autoload vendor
require 'vendor/autoload.php';

// Load functions
require __dir__ . '/functions/foldersize.php';
require __dir__ . '/functions/deletedirectory.php';
require __dir__ . '/functions/passwordgenerator.php';
require __dir__ . '/functions/mail.php';

// Load essentials
require __dir__ . '/connection/database.php';

require __dir__ . '/events/events.php';

require __dir__ . '/serverinfo/serverinfo.php';

require __dir__ . '/databases/databases.php';
require __dir__ . '/databases/backups.php';

require __dir__ . '/tickets/tickets.php';

require __dir__ . '/users/users.php';

require __dir__ . '/websites/screenshot.php';
require __dir__ . '/websites/websites.php';

// Load panel
require __dir__ . '/panel/panel.php';

?>
