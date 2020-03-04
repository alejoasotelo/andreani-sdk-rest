<!-- BADGES -->
[![Latest Stable Version](https://poser.pugx.org/alejoasotelo/andreani/v/stable)](https://packagist.org/packages/alejoasotelo/andreani)
[![License](https://poser.pugx.org/alejoasotelo/andreani/license)](https://packagist.org/packages/alejoasotelo/andreani)

![Andreani](https://miro.medium.com/max/236/1*SU6pjCbwtPaLTr27wQJgIQ.png)

# Andreani SDK Rest - PHP

Andreani SDK Rest es una librería para conectar con la Api Rest de Andreani (https://andreani.docs.apiary.io).

Es necesario para poder conectar tus credenciales de Andreani (usuario, contraseña y cliente).

Ejemplo:
Usuario: alejo
Password: sotelo
Cliente: CL0009999

### Artículo en medium

[Ver artículo](https://medium.com/@alejoasotelo/librer%C3%ADa-php-para-andreani-api-rest-128c109f4e0b)

### Instalación vía Composer

```bash
composer require alejoasotelo/andreani
```

### Cómo se utiliza la libreria?

La librería es sencilla, se puede ver un ejemplo en example.php
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

Obtener las sucursales recomendadas para un código postal:
```php
<?php
...

$ws = new Andreani($user, $pass, $cliente, $debug);
$result = $ws->getSucursalByCodigoPostal(1832);

var_dump($result);
```
