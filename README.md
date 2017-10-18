Feedback Order Reviews
================

Se incluye un PDF (manual.pdf) que especifica las configuraciones del modulo.

Descripción
-------------

Este modulo se ha creado para enviar al cliente o usuario invitado que ha realizado un pedido en el store un email una vez transcurridos N días para valorar
los siguientes puntos del pedido: Atención al cliente, producto y entrega, además dispone de un campo adicional para añadir comentarios.

Todos los usuarios podran ver en el frontend un listado de las opiniones de los pedidos que esten aprobadas por el administrador del Magento.

Incluye un apartado en el backend para administrar dichas opiniones, pudiendo eliminarlas o cambiarlas de estado.

Instruciones de instalación
-------------

- Via modman

Instalar [modman](https://github.com/colinmollenhour/modman)
Utiliza el comando en la carpeta de instalación de Magento: `modman clone https://github.com/lauragc88/reviews.git`

- Via composer

1 - Instalar [composer](http://getcomposer.org/download/)
2 - Instalar [Magento Composer](https://github.com/magento-hackathon/magento-composer-installer)
3 - Crear el fichero composer.json en su proyecto, ejemplo:

```json
{
    ...
    "require": {
        "lauragc88/reviews":"*"
    },
    "repositories": [
	    {
            "type": "vcs",
            "url": "https://github.com/lauragc88/reviews.git"
        }
    ],
    "extra":{
        "magento-root-dir": "./"
    }
}
```

4 - Luego, desde su carpeta `composer.json` ejecutamos lo siguiente: `php composer.phar install` o `composer install`
