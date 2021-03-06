<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, $debug);

// Datos de ejemplo obtenidos de https://developers.andreani.com/documentacion/2#crearOrden
$orden = [
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

$response = $ws->addOrden($orden);

var_dump($response);

if (!is_null($response)) {
    file_put_contents(__DIR__.'/addOrden.json', json_encode($response));
}
