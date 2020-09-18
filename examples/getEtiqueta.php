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

$response = $ws->getEtiqueta('360000003170610');

var_dump($response);

if (!is_null($response) && isset($response->pdf)) {
    file_put_contents(__DIR__.'/getEtiqueta.pdf', $response->pdf);
}