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
    sexo ENUM('MACHO', 'HEMBRA') NOT NULL,
    raza VARCHAR(50) NOT NULL,
    color VARCHAR(50) NOT NULL,
    -- Datos del propietario
    nombrePropietario VARCHAR(120) NOT NULL,
    curpPropietario VARCHAR(18) NOT NULL,
    domicilioPropietario VARCHAR(200) NOT NULL,
    -- Datos del trabajador social
    trabajadorSocial INT NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trabajadorSocial) REFERENCES usuarios (id) ON UPDATE CASCADE ON DELETE CASCADE
);