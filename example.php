<?php

require_once __DIR__.'/vendor/autoload.php';

use AlejoASotelo\Andreani;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = 'miuser';
$pass = 'mipass';
$cliente = 'CL9999999';
$debug = true;

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getProvincias();

var_dump($result);
