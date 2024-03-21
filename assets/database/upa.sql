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

-- Vista: Trabajador Social
CREATE VIEW pets AS
SELECT folio, petName, petPicture, ownerName, ownerColony, CONCAT(DAY(mp.fechaRegistro), '/', MONTH(mp.fechaRegistro), '/', YEAR(mp.fechaRegistro)) AS registerDate, CONCAT(users.nombre, ' ', users.apellido) AS tsName, users.id AS tsID
FROM mascotasPropietarios mp
INNER JOIN usuarios users ON mp.idTS = users.id
WHERE users.userType = 'ts';