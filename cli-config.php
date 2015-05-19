<?php
session_start();

require_once('vendor/autoload.php');
require_once ('lib/util/Database.php');

global $_EntityManager;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($_EntityManager);