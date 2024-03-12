# Web UPA de Valle de Chalco Solidaridad

## Lista de Pendientes:
* Conseguir logotipos oficiales
* Cambiar el logotipo del header por el oficial de la UPA
* Averiguar el telefono de la UPA (55 7251 3868 o 55 7251 3862)

# Warning Devs!
 * Falta añadir validadores de sesión en el index y en el login
 * Corregir el problema del mayus en los forms

# Datos para la migración
* Crear una BBDD con los parametros adecuados acorde al config.php
* En el php.ini buscar "session.gc_maxlifetime" y setearlo a 2592000
* Descomprimir el vendor.zip en la ruta en la que se encuentra

# Dependencias necesarias en PHP

extension=mbstring
extension=exif
extension=mysqli
extension=gd