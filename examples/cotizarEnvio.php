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

$bultos = array(
    array(
        'volumen' => 200,
        'kilos' => 1.3,
        'pesoAforado' => 5,
        'valorDeclarado' => 1200, // $1200
    ),
);

// Contrato y Cliente de ejemplo obtenidos de https://developers.andreani.com/documentacion/3#cotizarEnvio
$result = $ws->cotizarEnvio(1832, '300006611', $bultos, 'CL0003750');

var_dump($result);
