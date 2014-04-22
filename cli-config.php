<?php
// cli-config.php
require_once ('lib/MyCookieDatabase.php');

global $_EntityManager;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($_EntityManager);