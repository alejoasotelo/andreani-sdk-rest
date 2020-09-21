<?php

/**
 * Proceso de Envío
 * 
 * 1. Cotizar el envío
 * 2. Crear una Orden
 * 3. Obtener la orden
 * 4. Obtener la trazabilidad
 * 5. Obtener la etiqueta. *  
 */

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);


$contrato = '400006711';

// Datos de ejemplo obtenidos de https://developers.andreani.com/documentacion/2#crearOrden
$data = [
    'contrato' => '400006711',
    'origen' => [
        'postal' => [
            'codigoPostal' => '3378',
            'calle' => 'Av Falsa',
            'numero' => '380',
            'localidad' => 'Puerto Esperanza',
            'region' => '',
            'pais' => 'Argentina',
            'componentesDeDireccion' => [
                [
                    'meta' => 'entreCalle',
                    'contenido' => 'Medina y Jualberto',
                ],
            ],
        ],
    ],
    'destino' => [
      'postal' => [
        'codigoPostal' => '1292',
        'calle' => 'Macacha Guemes',
        'numero' => '28',
        'localidad' => 'C.A.B.A.',
        'region' => 'AR-B',
        'pais' => 'Argentina',
        'componentesDeDireccion' => [
          [
            'meta' => 'piso',
            'contenido' => '2',
          ],
          [
            'meta' => 'departamento',
            'contenido' => 'B',
          ],
        ],
      ],
    ],
    'remitente' => [
      'nombreCompleto' => 'Alberto Lopez',
      'email' => 'remitente@andreani.com',
      'documentoTipo' => 'DNI',
      'documentoNumero' => '33111222',
      'telefonos' => [
        [
          'tipo' => 1,
          'numero' => '113332244',
        ],
      ],
    ],
    'destinatario' => [
        [
            'nombreCompleto' => 'Juana Gonzalez',
            'email' => 'destinatario@andreani.com',
            'documentoTipo' => 'DNI',
            'documentoNumero' => '33999888',
            'telefonos' => [
                [
                    'tipo' => 1,
                    'numero' => '1112345678',
                ],
            ],
        ],
    ],
    'productoAEntregar' => 'Aire Acondicionado',
    'bultos' => [
        [
            'kilos' => 2,
            'largoCm' => 10,
            'altoCm' => 50,
            'anchoCm' => 10,
            'volumenCm' => 5000,
            'valorDeclaradoSinImpuestos' => 1200,
            'valorDeclaradoConImpuestos' => 1452,
            'referencias' => [
                [
                    'meta' => 'detalle',
                    'contenido' => 'Secador de pelo',
                ],
                [
                    'meta' => 'idCliente',
                    'contenido' => '10000',
                ],
            ],
        ],
    ],
];

### 1. Cotizar el envío ###
$codigoPostal = $data['destino']['postal']['codigoPostal'];

$cotizacion = $ws->cotizarEnvio($codigoPostal, $contrato, $data['bultos']);

if (is_null($cotizacion)) {
    die('1. (!) No se pudo obtener la Cotización.');
}

file_put_contents(__DIR__.'/procesoDeEnvio-1-cotizarEnvio.json', json_encode($cotizacion));


### 2. Crear la Orden ###
$orden = $ws->addOrden($data);

if (is_null($orden)) {
    die('2. (!) No se pudo crear la Orden.');
}

file_put_contents(__DIR__.'/procesoDeEnvio-2-addOrden.json', json_encode($orden));

// Como este envío es 1 solo bulto obtengo el primer item del array bultos
$numeroDeEnvio = $orden->bultos[0]->numeroDeEnvio;


### 3. Obtener la orden ###
$orden = $ws->getOrden($numeroDeEnvio);

if (is_null($orden)) {
    die('3. (!) No se pudo obtener la Orden.');
}

file_put_contents(__DIR__.'/procesoDeEnvio-3-getOrden.json', json_encode($orden));

### 4. Obtener la trazabilidad ###
$numeroDeEnvio = $orden->bultos[0]->numeroDeEnvio;
$trazabilidad = $ws->getTrazabilidad($numeroDeEnvio);

// cuando se genera una nueva orden la trazabilidad responde con código 404.
if (is_null($trazabilidad)) {
    $response = $ws->getResponse();

    if ($response->code == 404) {
        $trazabilidad = json_decode($response->body);
    } else {
        die('4. (!) No se pudo Obtener la Trazabilidad.');
    }
}

file_put_contents(__DIR__.'/procesoDeEnvio-4-getTrazatabilidad.json', json_encode($trazabilidad));

### 5. Obtener la etiqueta. ###
$etiqueta = $ws->getEtiqueta($numeroDeEnvio);

if (!is_null($etiqueta) && isset($etiqueta->pdf)) {
    file_put_contents(__DIR__.'/procesoDeEnvio-5-getEtiqueta.pdf', $etiqueta->pdf);
    die('¡Proceso completado OK!');
}

die('5. (!) No se pudo obtener la Etiqueta');
