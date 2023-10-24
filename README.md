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

## (❗) NOTA

- Desde la versión 0.8.0 se actualizaron las urls de desarrollo y producción. Es posible que tengas que pedir un nuevo usuario y contraseña para que funcionen con estas nuevas urls. En este caso es necesario que te contactes con el webservice o tu agente de Andreani.
- Desde la versión 0.9.0 se eliminó la función `getSucursalByCodigoPostalLegacy`.

## Cómo se utiliza la libreria?

Se pueden ver ejemplos de uso en la carpeta [examples](examples). Para poder ejecutarlos es necesario renombrar el archivo [config.json.dist](examples/config.json.dist) a config.json y reemplazar las credenciales.

## Funciones

Ver documentación de las funciones en [DOCS.md](https://github.com/alejoasotelo/andreani-sdk-rest/blob/de4d77a3a245976859684e8ee0e275628aa9389f/DOCS.md).

## Contacto API Andreani
Si tenés dudas respecto a la API de Andreani este es el email de ellos: apis[arroba]andreani.com

(!) Aclaración: este es un proyecto personal, yo no tengo ningún tipo de vinculo con Andreani, comparto mi conocimiento en este repositorio.


## Querés colaborar con el proyecto?

Podés enviar tu mejoras o pull request o podés
[![Invitarme un café en cafecito.app](https://cdn.cafecito.app/imgs/buttons/button_2.svg)](https://cafecito.app/alejoasotelo)