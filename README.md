# Sistema de Control y Gestión de Mascotas - UPA

Este proyecto fue realizado para la Unidad de Protección Animal del municipio de Valle de Chalco Solidaridad entre Febrero y Abril de 2024 durante mi periodo de estancias y prácticas profesionales dentro del departamento de sistemas del mismo municipio.

## Índice

1. [Descripción](#descripción)
2. [Tecnologías Utilizadas](#tecnologías-utilizadas)
3. [Instalación](#instalación)
4. [Contraseñas de Superadministrador](#contraseñas-de-administrador-superadministrador)
5. [Contraseñas de Administrador (UPA)](#contraseñas-de-administrador-upa)
6. [Autor](#autor)


## Descripción

El sistema desarrollado para la UPA es un pequeño sistema CRUD con diferentes tipos de usuarios y privilegios, esta almacena datos de mascotas en una base de datos que posteriormente es consultada para generar una acta en formato .pdf con los datos de la mascota y así llevar un mejor control de las mascotas de los habitantes del municipio.

## Tecnologías Utilizadas

* PHP
* JavaScript
* HTML/CSS

## Instalación

<b style="color: red;">LA EXPLICACIÓN PODRÍA SER  MUY AMBIGUA DEBIDO A LA DIFERENCIA ENTRE EMPRESAS DE HOSTING</b>

Si el proyecto desea instalarse de manera local basta con configurar el config.php y el php.ini (pasos 2 y 3) y tener MySQL instalado en el equipo. Ahora bien, la siguiente explicación esta ideada para la instalación del proyecto dentro de un hosting compartido.

__1. Creación de la base de datos__
* __Debemos acceder al CPANEL y buscar el apartado relacionado a bases de datos__ _(Este apartado puede tener otro nombre según el plan y empresa de hosting)_

    ![img_1](/assets/readme/1.png)

* __Crear una base de datos y un usuario con todos los privilegios.__

    ![img_2](/assets/readme/2.png)

    ![img_3](/assets/readme/3.png)

* __Agregar el usuario creado a la base de datos creada con todos los privilegios seleccionados.__

    ![img_4](/assets/readme/4.png)

    ![img_5](/assets/readme/5.png)

    ![img_6](/assets/readme/6.png)

* __Finalmente, nos dirigiremos a phpMyAdmin__

    ![img_9](/assets/readme/9.png)

* Aquí copiaremos el script de sql ubicado en <code>assets/database/upa (instalacion).sql</code> del cual copiaremos su contendio y pegaremos en la pestaña SQL de nuestra base de datos, daremos en "CONTINUAR".

    ![img_10](/assets/readme/10.png)

    Si todo esta bien __todas las consultas de MySQL deberían mostrarse en verde__.

__2. Configurar el archivo config.php__
* Dentro de la carpeta config en la raíz del proeycto _(si no esta basta con crearla)_ crear o buscar el archivo <code>config.php</code>
y configurarlo de la siguiente manera: 

```php
<?php
//localhost:puerto, el puerto es practicamente siempre el 3306
$servername = "localhost:3306"; 
//Nombre del usuario que acabamos de crear
$mysql_username = "amberage_upa"; 
// Contraseña que usamos para el usuario
$mysql_password = "4%^vBIXHCmx!"; 
// Nombre de la base de datos que acabamos de crear
$dbname = "amberage_UPA"; 

/*Ruta absoluta de donde se cargara el proyecto en el hosting la cual podemos
consultar desde el administrador de archivos de CPANEL*/
$pathPicturesReplace = '/home4/amberage/public_html'; 
//Este siempre se mantiene en blanco
$pathChars = '';
?>
```
__3. Configurar el servicio PHP del servidor__

* Finalmente se debe configurar el servicio PHP del servidor, lo que localmente sería el <code>php.ini</code>, sin embargo, en la mayoria de los casos los proovedores de hosting en sus planes básicos no ofrecen la manipulación directa de este archivo y debemos crearlo por cuenta propia.

```ini
extension=mbstring
extension=exif
extension=mysqli
extension=gd
post_max_size = 20M
upload_max_filesize = 20M
session.gc_maxlifetime = 2592000
```
Del listado anterior, prácticamente todas estan activas por default, salvo las últimas tres opciones que deberemos configurar manualmente. Es importante revisar que las extensiones esten activas, cuando se empiece a testear el proyecto.

Deberemos dirigirnos al apartado que nos permita modificar el <code>php.ini</code>, en el caso de Hostgator es el siguiente:

![img_7](/assets/readme/7.png)

Aquí configuraremos <code>post_max_size</code>, <code>upload_max_filesize</code> y <code>session.gc_maxlifetime</code>, ya que como se mencionó, el resto de los parametros ya vienen activos por default en practicamente todos los serivicios de hosting compartido.

![img_8](/assets/readme/8.png)

__4. Cargar todo el sistema a la página web__

Finalmente, deberemos cargar todo el proyecto con las carpetas que se muestran a continuación dentro de la carpeta de nuestro hosting:

![img_11](/assets/readme/11.png)

Es ideal seleccionar estas carpetas y meterlas en un <code>zip</code> para agilizar el proceso.

![img_12](/assets/readme/12.png)

__ES IMPORTANTE QUE EL PROYECTO SE ENCUENTRE COMO SE MUESTRA EN LA DERECHA DE LA IMAGEN, YA QUE LAS RUTAS ESTAN CONFIGURADAS PARA TRABAJAR EN ESA DISPOSICIÓN, DE LO CONTRARIO EL PROYECTO NO FUNCIONARÁ ADECUADAMENTE__

### Después de instalar...
A este punto, de todo estar en orden el proyecto debería funcionar adecuadamente, aún asi es ideal testear todas sus funcionalidades y buscar constantemente dentro de la carpetas  <code>/</code>,  <code>config</code>,  <code>php</code> y todas sus subcarpetas un fichero llamado <code>error_log</code> el cual guarda el registro de todos los errores del sistema durante la ejecución del mismo. Allí podremos averiguar si algo no funciona como debería.

## Contraseñas de administrador (Superadministrador)

Por default el sistema maneja la siguientes credenciales para el superadminsitrador, las cuales deben ser __CONFIDENCIALES__ dentro del departamento de sistemas y son exclusivas del departamento.

Estas credenciales __NO SON MODIFICABLES__ por medios tradicionales, por tal motivo solo deberían usarse como último recurso para acceder al panel de superadministrador y reestablecer el sistema.

* Credenciales del superadministrador
```ini
usuario = sistemasVACH
contrasena = 5yg%gpmcv^$&93^4D4AnUr5ACHakDAw9
```
* Token de acceso al panel de superadministrador
```yaml
bFP-.gv0Mo1z1-jgf4'OAA(skBuW1=;u56bH{#3j-6"H}tz;D.R:oU(H;@o13Pk#
```

## Contraseñas de administrador (UPA)

Las siguientes credenciales son para proporcionaselas a la UPA, estas credenciales a diferencia de las del superadministrador __SON MODIFICABLES__ y tienen como finalidad llevar la gestión de los trabajadores de la UPA y las actas generadas por el sistema.

* Credencial #1
```ini
usuario = admin
contrasena = ?2KJ9pcYb!
```
* Credencial #2
```ini
usuario = UPAVACH
contrasena = SisUPa.23@#
```
### _Autor_

_Desarrollado por Roberto Galicia en abril de 2024, por parte del proyecto de estancias profesionales para poder obtener el grado de Ingeniero en Computación._