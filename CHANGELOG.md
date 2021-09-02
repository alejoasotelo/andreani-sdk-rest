# Changelog

## 0.8.1 - (2021-09-02)

### Changed

- Corregí el error "Undefined index: x-authorization-token"

## 0.8.0 - (2021-09-02)

### Changed

- resolve [#7](/alejoasotelo/andreani-sdk-rest/issues/7) Actualicé los base_url de producción y desarrollo y agregué compatibilidad con el header x-authorization-token (en minúscula).


## 0.7.0 - (2021-07-15)

### Added

- Agregué [getCodigoQR](/alejoasotelo/andreani-sdk-rest/blob/010d8d242651e13893d53bc4d36837f845607cbb/src/Andreani.php#L352) para poder generar códigos QR.
- Agregué un ejemplo de uso de [getCodigoQR](/alejoasotelo/andreani-sdk-rest/blob/010d8d242651e13893d53bc4d36837f845607cbb/examples/getCodigoQR.php).
- Agregué un ejemplo de uso de [getSucursales](/alejoasotelo/andreani-sdk-rest/blob/010d8d242651e13893d53bc4d36837f845607cbb/examples/getSucursales.php).

### Changed

- Se cambió la firma de `getSucursales($version)` a [`getSucursales($params, $version)`](/alejoasotelo/andreani-sdk-rest/blob/010d8d242651e13893d53bc4d36837f845607cbb/src/Andreani.php#L113)


## 0.6.0 - (2021-07-07)

### Added

- Agregué la función [getVersion](/alejoasotelo/andreani-sdk-rest/blob/6e800018a0dfbff6ffd26bf6f0340440733c0ea6/src/Andreani.php#L58) para saber la versión de la librería

### Changed

- Agregué el argumento $tipo (ETIQUETA_ESTANDAR o ETIQUETA_DOCUMENTO_DE_CAMBIO) a la función [getEtiqueta](/alejoasotelo/andreani-sdk-rest/blob/6e800018a0dfbff6ffd26bf6f0340440733c0ea6/src/Andreani.php#L241) para poder obtener las etiquetas estandar y el documento de cambio para los envíos de tipo cambio.
- Agregué el argumento $apiVersion a la función [getTrazabilidad](/alejoasotelo/andreani-sdk-rest/blob/6e800018a0dfbff6ffd26bf6f0340440733c0ea6/src/Andreani.php#L295) para poder usar la v1 o v2.


## 0.5.1 - (2020-09-21)

### Changed

- Cambié la licencia a GNU GPL v2

## 0.5.0 - (2020-09-18)

### Added

- Agregué una config general para todos los ejemplos. Las credenciales ahora se configuraron en el archivo examples/config.json
- Agregué un ejemplo del proceso completo de envío.

### Changed

- Corregí la función getEtiqueta para que use la v2 y agregué un ejemplo de cómo usarla.
- Eliminé la función removeOrden porque no se puede eliminar ordenes con la api rest. Un envío se considera cancelado si no entra en distribución.
- Modifiqué el ejemplo cotizarEnvio para que guarde el response en un json.

## 0.4.0 - (2020-08-17)

### Added

- Agregué [getSucursalByCodigoPostalLegacy](/alejoasotelo/andreani-sdk-rest/blob/74b0431fda8adecedc75b4257caaa83cfb771eb5/src/andreani.php#L137) para poder seguir utilizando la Api SOAP hasta que la deprequen.
- Agregué la función [getDirecciones](/alejoasotelo/andreani-sdk-rest/blob/74b0431fda8adecedc75b4257caaa83cfb771eb5/src/andreani.php#L167) para obtener la geolocalización de una dirección.
- Agregué CHANGELOG.

### Changed

- [getSucursalByCodigoPostal](/alejoasotelo/andreani-sdk-rest/blob/74b0431fda8adecedc75b4257caaa83cfb771eb5/src/andreani.php#L113) ahora utiliza la api versión 2.
- Cambié la versión de getSucursales a la v2 por default, con un parametro para poder cambiar a la v1.

## 0.3.0 - (2020-08-12)

### Changed

- Cambié a la versión 2 el endpoint de [getOrden](/alejoasotelo/andreani-sdk-rest/blob/74b0431fda8adecedc75b4257caaa83cfb771eb5/src/andreani.php#L218).