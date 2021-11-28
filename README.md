# ApiPaymentAnalytics

Api para la creación, actualización y lectura de producto y tickets, y la obtenció de datos estadísticos sobre estos mismos.

## Usage
La comunicació con la api se utiliza mediante llamadas post de tipo json, y devuelve el mismo tipo de datos.
## API/Component
### Product
ruta padre por defecto /product
####/new
función para crear nuevos productos, no recibe ningun parámetro y devuelve el producto creado
####/read
función para ver los detalles de un producto, recibe un id de producto y devuelve el producto
####/update
función para actualizar un producto, recibe un id de producto y devuelve este producto actualizado
####/delete
función para eliminar un producto, recibe un id de producto y devuelve un string con el id del producto eliminado
### Ticket
####/new
función para crear nuevos tickets, no recibe ningun parámetro y devuelve el ticket creado
####/read
función para ver los detalles de un ticket, recibe un id de ticket y devuelve el ticket
####/delete
función para eliminar un ticket, recibe un id de ticket y devuelve un string con el id del ticket eliminado
####/analytics
función para consultar datos estadísticos, no recibe ningún parámetro y devuelve un array de datos
## Installation

```shell
    # Clone or install commands
    git clone https://github.com/squashi91/ApiBackendHackaton.git
```

## Stack

Se utiliza el stack LAMP
####Linux
####Apache
####MariaDB
####PHP
Se utiliza Mariadb en lugar de mysql porque despúes de realizar distintas pruebas estos 2 años como programador, he visto que maria db es un poco más rápido a la hora de realizar consultas grandes en BDD
