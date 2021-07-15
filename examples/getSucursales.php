<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, false);

$response = $ws->getSucursales(['canal' => 'B2C']);

if (!is_null($response)) {
    file_put_contents(__DIR__.'/getSucursales.json', json_encode($response));
}