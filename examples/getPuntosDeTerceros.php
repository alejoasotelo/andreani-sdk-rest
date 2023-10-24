<?php

include_once __DIR__ . '/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, false);

$response = $ws->getPuntosDeTerceros('300006611', [
    'canal' => 'B2C',
    'localidad' => 'Lomas de Zamora'
]);

if (!is_null($response)) {
    file_put_contents(__DIR__ . '/getPuntosDeTerceros.json', json_encode($response));
    exit(0);
}

var_dump($ws->getResponse());
exit(1);

