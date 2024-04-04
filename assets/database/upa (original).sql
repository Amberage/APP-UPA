/*El nombre de la base de datos puede variar según el hosting, 
algunos permiten crearla directamente por CLI en otros 
es necesario crearla desde el panel del hosting */

-- !! Este archivo es el que se uso para desarrollar el proyecto usando Navicat, sin embargo, crear la base de datos en phpMyAdmin con este archivo
-- DARA ERRORES, por lo que el fichero adecuado es el llamado upa (instalacion).sql !!
CREATE DATABASE UPA;
USE UPA

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    username VARCHAR(12) NOT NULL,
    password VARCHAR(4096) NOT NULL,
    -- adm: Admin | ts: Trabajador Social
    userType ENUM('adm', 'ts') NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mascotasPropietarios(
    -- Esta tabla corresponde a los registros, de aquí se extraen los datos para las actas.
    folio INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos de la mascota
    petName VARCHAR(120) NOT NULL,
    petBreed VARCHAR(50) NOT NULL,
    petColor VARCHAR(50) NOT NULL,
    petSex ENUM('MACHO', 'HEMBRA') NOT NULL,
    petPicture VARCHAR(255) NOT NULL,
    -- Datos del propietario
    ownerName VARCHAR(200) NOT NULL,
    ownerINE VARCHAR(26) NOT NULL,
    ownerCURP VARCHAR(18) NOT NULL,
    ownerColony VARCHAR(200) NOT NULL,
    ownerAddress VARCHAR(255) NOT NULL,
    -- Datos del trabajador social
    idTS INT NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idTS) REFERENCES usuarios (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE backup_mascotasPropietarios(
    -- Esta tabla corresponde a los registros, de aquí se extraen los datos para las actas.
    folio INT PRIMARY KEY,
    -- Datos de la mascota
    petName VARCHAR(120) NOT NULL,
    petBreed VARCHAR(50) NOT NULL,
    petColor VARCHAR(50) NOT NULL,
    petSex ENUM('MACHO', 'HEMBRA') NOT NULL,
    petPicture VARCHAR(255) NOT NULL,
    -- Datos del propietario
    ownerName VARCHAR(200) NOT NULL,
    ownerINE VARCHAR(26) NOT NULL,
    ownerCURP VARCHAR(18) NOT NULL,
    ownerColony VARCHAR(200) NOT NULL,
    ownerAddress VARCHAR(255) NOT NULL,
    -- Datos del trabajador social
    idTS INT NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idTS) REFERENCES usuarios (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE tokenSuperUser(
    token varchar(128)
);

-- Vista: Trabajador Social
CREATE VIEW pets AS
SELECT folio, petName, petPicture, ownerName, ownerColony, CONCAT(DAY(mp.fechaRegistro), '/', MONTH(mp.fechaRegistro), '/', YEAR(mp.fechaRegistro)) AS registerDate, CONCAT(users.nombre, ' ', users.apellido) AS tsName, users.id AS tsID
FROM mascotasPropietarios mp
INNER JOIN usuarios users ON mp.idTS = users.id
WHERE users.userType = 'ts';

-- Vista: Administrador
CREATE VIEW adminPets AS
SELECT folio, petName, petPicture, ownerName, ownerColony, CONCAT(DAY(mp.fechaRegistro), '/', MONTH(mp.fechaRegistro), '/', YEAR(mp.fechaRegistro)) AS registerDate, CONCAT(users.nombre, ' ', users.apellido) AS tsName, users.id AS tsID
FROM backup_mascotasPropietarios mp
INNER JOIN usuarios users ON mp.idTS = users.id
WHERE users.userType = 'ts' || users.userType = 'adm';


-- Triggers para clonar la información de la tabla original en la del respaldo.
-- Nota: Esto cumple dos funciones, tener un respaldo de la BBDD y llevar un conteo de los registros reales en la tabla de mascotas.
-- IF @recoveryBackup IS NULL THEN, si se establece una variable de sesión al hacer la query, el trigger no se activara, es util para poder restaurar información desde el backup sin activar los triggers y provocar errores de duplicidad
CREATE TRIGGER insertBackup AFTER INSERT ON mascotasPropietarios
FOR EACH ROW
BEGIN
    IF @recoveryBackup IS NULL THEN
        INSERT INTO backup_mascotasPropietarios 
        (folio, petName, petBreed, petColor, petSex, petPicture, ownerName, ownerINE, ownerCURP, ownerColony, ownerAddress, idTS, fechaRegistro) 
        VALUES 
        (NEW.folio, NEW.petName, NEW.petBreed, NEW.petColor, NEW.petSex, NEW.petPicture, NEW.ownerName, NEW.ownerINE, NEW.ownerCURP, NEW.ownerColony, NEW.ownerAddress, NEW.idTS, NEW.fechaRegistro);
    END IF;
END;

CREATE TRIGGER updateBackup AFTER UPDATE ON mascotasPropietarios
FOR EACH ROW
BEGIN
    IF @recoveryBackup IS NULL THEN
        UPDATE backup_mascotasPropietarios 
        SET 
        petName = NEW.petName,
        petBreed = NEW.petBreed,
        petColor = NEW.petColor,
        petSex = NEW.petSex,
        ownerName = NEW.ownerName,
        ownerINE = NEW.ownerINE,
        ownerCURP = NEW.ownerCURP,
        ownerColony = NEW.ownerColony,
        ownerAddress = NEW.ownerAddress,
        idTS = NEW.idTS,
        fechaRegistro = NEW.fechaRegistro
        WHERE folio = NEW.folio;
    END IF;
END;

-- Valores default
INSERT INTO usuarios (id, nombre, apellido, username, password, userType, fechaRegistro)
VALUES 
(1, 'Departamento de Sistemas', 'Valle de Chalco Solidaridad', 'sistemasVACH', '$2y$10$A2ptE8PxOnLhAClidNKRNOVe0Pja5OH2iqaMF9t5QtwfLujPasljG', 'adm', '2000-01-01 00:00:00'),
(2, 'Administrador', 'UPA', 'admin', '$2y$10$JMi/XGgXMGo8QLbHTz5IOe0/MMlzVxbN8BBhfQIYUrC4oZqMgqip6', 'adm', '2000-01-01 00:00:00'),
(3, 'UPA', 'Valle de Chalco Solidaridad', 'UPAVACH', '$2y$10$HV5e7mPDEjcqehD.wJma.eCEMiSN941v9ar/AqPse1lQqOCZUhsPu', 'adm', '2000-01-01 00:00:00');

INSERT INTO tokenSuperUser (token) VALUES ('bFP-.gv0Mo1z1-jgf4\'OAA(skBuW1=;u56bH{#3j-6"H}tz;D.R:oU(H;@o13Pk#');
