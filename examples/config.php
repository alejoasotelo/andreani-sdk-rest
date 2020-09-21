<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use Joomla\Registry\Registry;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = new Registry;
$config->loadFile(__DIR__ . '/config.json', 'json');

