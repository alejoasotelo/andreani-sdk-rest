<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use AlejoASotelo\Andreani;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = 'miuser';
$pass = 'mipass';
$cliente = 'CL9999999';
$debug = true;

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