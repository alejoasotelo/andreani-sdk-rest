# Changelog

## 0.4.0 - (2020-08-17)

### Added

- Agregué [getSucursalByCodigoPostalLegacy](src/andreani.php#L137) para poder seguir utilizando la Api SOAP hasta que la deprequen.
- Agregué la función [getDirecciones](src/andreani.php#L167) para obtener la geolocalización de una dirección.
- Agregué CHANGELOG.

### Changed

- [getSucursalByCodigoPostal](src/andreani.php#L113) ahora utiliza la api versión 2.
- Cambié la versión de getSucursales a la v2 por default, con un parametro para poder cambiar a la v1.

## 0.3.0 - (2020-08-12)

### Changed

- Cambié a la versión 2 el endpoint de [getOrden](src/andreani.php#L218).