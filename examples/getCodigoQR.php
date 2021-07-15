<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$numeroDeEnvio = '360000003147060';

$response = $ws->getCodigoQR(json_encode(['numeroDeEnvio' => $numeroDeEnvio]));

if (!empty($response)) {
    file_put_contents(__DIR__.'/getCodigoQR.png', $response);
}