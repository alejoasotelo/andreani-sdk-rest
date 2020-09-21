<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$numeroDeEnvio = '360000001111111';

$response = $ws->getEnvio($numeroDeEnvio);

var_dump($response);

if (!is_null($response)) {
    file_put_contents(__DIR__.'/getEnvio.json', json_encode($response));
}
