# Changelog

## 0.4.0 - (2020-08-17)

### Added

- Agregué getSucursalByCodigoPostalLegacy para poder seguir utilizando la Api SOAP hasta que la deprequen.
- Agregué la función getDirecciones para obtener la geolocalización de una dirección.
- Agregué CHANGELOG.

### Changed

- getSucursalByCodigoPostal ahora utiliza la api versión 2.
- Cambié la versión de getSucursales a la v2 por default, con un parametro para poder cambiar a la v1.

## 0.3.0 - (2020-08-12)

### Changed

- Cambié a la versión 2 el endpoint de getOrden.