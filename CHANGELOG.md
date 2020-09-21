# Changelog

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