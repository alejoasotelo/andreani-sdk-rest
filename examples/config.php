<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use Joomla\Registry\Registry;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('FILEPATH_CONFIG', __DIR__ .'/config.json');

if (!file_exists(FILEPATH_CONFIG)) {
    die('Falta el archivo config.json. Renombre el archivo config.json.dist a config.json e ingrese sus crendeciales de Andreani.');
}

$config = new Registry;
$config->loadFile(FILEPATH_CONFIG, 'json');

