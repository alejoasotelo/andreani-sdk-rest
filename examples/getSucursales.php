<?php

include_once __DIR__.'/config.php';

use AlejoASotelo\Andreani;

$user = $config->get('user');
$pass = $config->get('pass');
$cliente = $config->get('cliente');
$debug = $config->get('debug', true);

$ws = new Andreani($user, $pass, $cliente, false);

$result = $ws->getSucursales(['canal' => 'B2C']);

$sucursales = [];
foreach ($result as $sucursal) {

    $dir = $sucursal->direccion;

    if (!empty($dir->provincia) && !empty($dir->localidad) && !empty($dir->calle)) {

        $row = [
            'id_sucursal' => '"'.$sucursal->numero.'"',
            'provincia' => '"'.addslashes($dir->provincia).'"',
            'ciudad' => '"'.addslashes($dir->localidad).'"',
            'direccion' => '"'.addslashes($dir->calle.' '.$dir->numero.', '.$dir->codigoPostal.', '.$dir->localidad.', '.$dir->provincia).'"',
            'descripcion' => '"'.addslashes($sucursal->descripcion).'"',
            'hora_de_trabajo' => '"'.addslashes($sucursal->horarioDeAtencion).'"',
            'atencion_al_cliente' => $sucursal->datosAdicionales->seHaceAtencionAlCliente ? 1 : 0
        ];
    
        $sucursales[] = '('.implode(',', $row).')';

    }

}

$sql = 'INSERT INTO #__saandreani_sucursales_v2 
    (id_sucursal, provincia, ciudad, direccion, descripcion, hora_de_trabajo, atencion_al_cliente)
    VALUES '.implode(', ', $sucursales).';';

echo $sql;