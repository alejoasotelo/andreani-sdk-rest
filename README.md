<!-- BADGES -->
[![Latest Stable Version](https://poser.pugx.org/alejoasotelo/andreani/v/stable)](https://packagist.org/packages/alejoasotelo/andreani)
[![License](https://poser.pugx.org/alejoasotelo/andreani/license)](https://packagist.org/packages/alejoasotelo/andreani)

![Andreani](https://miro.medium.com/max/236/1*SU6pjCbwtPaLTr27wQJgIQ.png)

# Andreani SDK Rest - PHP

Andreani SDK Rest es una librería para conectar con la Api Rest de Andreani ([ver documentación](https://developers.andreani.com/documentacion)).

Es obligatorio para poder conectar con Andreani tus credenciales: usuario, contraseña y cliente.

### Credenciales Obligatorias:
```bash
Usuario: alejo
Password: sotelo
Cliente: CL0009999
```

## Instalación vía Composer

```bash
composer require alejoasotelo/andreani
```

## Artículo en medium

[Ver artículo](https://medium.com/@alejoasotelo/librer%C3%ADa-php-para-andreani-api-rest-128c109f4e0b)

## Cómo se utiliza la libreria?

Se pueden ver ejemplos de uso en la carpeta [examples](examples). Para poder ejecutarlos es necesario renombrar el archivo [config.json.dist](examples/config.json.dist) a config.json y reemplazar las credenciales.

## Funciones

### Inicialización

```php
require_once __DIR__.'/vendor/autoload.php';

use AlejoASotelo\Andreani;

$ws = new Andreani($user, $pass, $cliente, $debug);
```

### getProvincias()

Lista las provinicas reconocidas según [ISO-3166-2:AR](https://es.wikipedia.org/wiki/ISO_3166-2:AR):

```php
$result = $ws->getProvincias();

var_dump($result);
```


### getSucursales()

Obtener todas las sucursales de Andreani:
```php
$result = $ws->getSucursales();

var_dump($result);
```


### getSucursalByCodigoPostal($codigoPostal)

Obtener las sucursales recomendadas para un código postal usando la api v2:
```php
$result = $ws->getSucursalByCodigoPostal(1832);

var_dump($result);
```


### getSucursalByCodigoPostalLegacy($codigoPostal)

Obtener las sucursales recomendadas para un código postal usando la api SOAP:
```php
$result = $ws->getSucursalByCodigoPostalLegacy(1832);

var_dump($result);
```


### cotizarEnvio($cpDestino, $contrato, $bultos)

Obtener la cotización para un envío según código postal, contrato, bultos, cliente, etc:
```php
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


### getEtiqueta($numeroAndreani)

Devuelve una etiqueta en formato PDF, que puede ser de bulto o remito a partir del numero andreani brindado en el alta. 

```php
$response = $ws->getEtiqueta($numeroAndreani);

if (!is_null($response) && isset($response->pdf)) {
    file_put_contents(__DIR__.'/getEtiqueta.pdf', $response->pdf);
}
```

Ver ejemplo en el archivo [examples/getEtiqueta.php](examples/getEtiqueta.php)

### (!) Cancelar envíos

En la nueva API no se pueden cancelar envíos. Andreani toma como cancelado un envío si no entra en distribución.

### Proceso completo de Envío

En el archivo [examples/procesoDeEnvio.php](examples/procesoDeEnvio.php) hay un ejemplo del proceso completo de envío. En cada paso se guarda el response en json y la etiqueta PDF en el último.

Proceso de Envío:
```txt
1. Cotizar el Envío
2. Crear una Orden
3. Obtener la Orden
4. Obtener la Trazabilidad
5. Obtener la Etiqueta.
```

## Contacto API Andreani

Si tenés dudas respecto a la API de Andreani este es el email de ellos: apis[arroba]andreani.com

(!) Aclaración: este es un proyecto personal, yo no tengo ningún tipo de vinculo con Andreani, comparto mi conocimiento en este repositorio.

## Querés colaborar con el proyecto?

Podés enviar tu mejoras o pull request o podés
[![Invitarme un café en cafecito.app](https://cdn.cafecito.app/imgs/buttons/button_2.svg)](https://cafecito.app/alejoasotelo)