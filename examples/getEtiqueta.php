<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

$response = $ws->getEtiqueta('360000003170610', Andreani::ETIQUETA_ESTANDAR);

var_dump($response);

if (!is_null($response) && isset($response->pdf)) {
    file_put_contents(__DIR__.'/getEtiqueta.pdf', $response->pdf);
}
