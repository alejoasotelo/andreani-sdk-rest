<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$result = $ws->getProvincias();

var_dump($result);
