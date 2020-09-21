<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$numeroDeEnvio = '360000003147060';

$response = $ws->getTrazabilidad($numeroDeEnvio);

// cuando se genera una nueva orden la trazabilidad responde con código 404.
if (is_null($response)) {
    $response = $ws->getResponse();

    if ($response->code == 404) {
        $response = json_decode($response->body);
    }
}
var_dump($response);

if (!is_null($response)) {
    file_put_contents(__DIR__.'/getTrazabilidad.json', json_encode($response));
}
