# Changelog

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