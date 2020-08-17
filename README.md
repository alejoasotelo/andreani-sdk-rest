<!-- BADGES -->
[![Latest Stable Version](https://poser.pugx.org/alejoasotelo/andreani/v/stable)](https://packagist.org/packages/alejoasotelo/andreani)
[![License](https://poser.pugx.org/alejoasotelo/andreani/license)](https://packagist.org/packages/alejoasotelo/andreani)

![Andreani](https://miro.medium.com/max/236/1*SU6pjCbwtPaLTr27wQJgIQ.png)

# Andreani SDK Rest - PHP

Andreani SDK Rest es una librería para conectar con la Api Rest de Andreani ([ver documentación](https://developers.andreani.com/documentacion)).

Es necesario para poder conectar tus credenciales de Andreani (usuario, contraseña y cliente).

### Ejemplo:
```bash
Usuario: alejo
Password: sotelo
Cliente: CL0009999
```

## Artículo en medium

[Ver artículo](https://medium.com/@alejoasotelo/librer%C3%ADa-php-para-andreani-api-rest-128c109f4e0b)

## Instalación vía Composer

```bash
composer require alejoasotelo/andreani
```


## Cómo se utiliza la libreria?

La librería es sencilla, se puede ver ejemplos en la carpeta [examples](examples).


### getProvincias()

Lista las provinicas reconocidas según [ISO-3166-2:AR](https://es.wikipedia.org/wiki/ISO_3166-2:AR):

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use AlejoASotelo\Andreani;

$user = 'miuser';
$pass = 'mipass';
$cliente = 'CL9999999';
$debug = true;

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getProvincias();

var_dump($result);
```


### getSucursales()

Obtener todas las sucursales de Andreani:
```php
<?php
...

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getSucursales();

var_dump($result);
```


### getSucursalByCodigoPostal($codigoPostal)

Obtener las sucursales recomendadas para un código postal usando la api v2:
```php
<?php
...

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getSucursalByCodigoPostal(1832);

var_dump($result);
```


### getSucursalByCodigoPostalLegacy($codigoPostal)

Obtener las sucursales recomendadas para un código postal usando la api SOAP:
```php
<?php
...

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getSucursalByCodigoPostalLegacy(1832);

var_dump($result);
```


### cotizarEnvio($cpDestino, $contrato, $bultos)

Obtener la cotización para un envío según código postal, contrato, bultos, cliente, etc:
```php
<?php
...

$ws = new Andreani($user, $pass, $cliente, $debug);

$bultos = array(
    array(
        'volumen' => 200,
        'kilos' => 1.3,
        'pesoAforado' => 5,
        'valorDeclarado' => 1200, // $1200
    ),
);

$result = $ws->cotizarEnvio(1832, '300006611', $bultos, 'CL0003750');

var_dump($result);
```

Ver ejemplo en el archivo [examples/cotizarEnvio.php](examples/cotizarEnvio.php)


### addOrden($data)

Agrega/crea una orden (envío) pasandole como parámetro $data con la info del envío. Puede ser pasado como un array o como string (json_encode).

Ver ejemplo en el archivo [examples/addOrden.php](examples/addOrden.php)