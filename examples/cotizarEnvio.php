<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$bultos = array(
    array(
        'volumen' => 200,
        'kilos' => 1.3,
        'pesoAforado' => 5,
        'valorDeclarado' => 1200, // $1200
    ),
);

// Contrato y Cliente de ejemplo obtenidos de https://developers.andreani.com/documentacion/3#cotizarEnvio
$response = $ws->cotizarEnvio(1832, '300006611', $bultos, 'CL0003750');

var_dump($response);

if (!is_null($response)) {
    file_put_contents(__DIR__.'/cotizarEnvio.json', json_encode($response));
}
