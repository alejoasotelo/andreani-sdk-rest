<!-- BADGES -->
[![Latest Stable Version](https://poser.pugx.org/alejoasotelo/andreani/v/stable)](https://packagist.org/packages/alejoasotelo/andreani)
[![License](https://poser.pugx.org/alejoasotelo/andreani/license)](https://packagist.org/packages/alejoasotelo/andreani)

Andreani SDK Rest - PHP
=================
Andreani SDK Rest es una librería para conectar con la Api Rest de Andreani (https://andreani.docs.apiary.io).

Es necesario para poder conectar tus credenciales de Andreani (usuario, contraseña y cliente).

Ejemplo:
Usuario: alejo
Password: sotelo
Cliente: CL0009999

Cómo se utiliza la libreria?
==========================
La librería es sencilla, se puede ver un ejemplo en example.php
```php
<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/andreani.php';

use AlejoASotelo\Andreani;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = 'miuser';
$pass = 'mipass';
$cliente = 'CL9999999';
$debug = true;

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getSucursales();

var_dump($result);
```
