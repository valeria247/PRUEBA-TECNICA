# PRUEBA-TECNICA

CRUD personas/departamentos

## Requisitos

- Base de Datos: PostgreSQL
- Editor de Código: Visual Studio Code (VSCode)
- Servidor Local: XAMPP

## Instalación

Sigue estos pasos para configurar tu proyecto en tu máquina local:

1. Abrir XAMPP y activar sus servicio Apache
2. Clonar el repositorio (nombre del archivo: 'prueba')
3. Crear la BD PostgreSQL(puerto:5432)


## SCRIPT DB PostgreSQL

CREATE DATABASE prueba;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL
);

## Ej
INSERT INTO usuarios (usuario, contrasena) VALUES ('smart', 'smart');

CREATE TABLE menu (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INT
);

## Ej
INSERT INTO menu (nombre, url, orden) 
VALUES 
    ('Personas', '../menu/personas.php', 1),
    ('Departamentos', '../menu/departamentos.php', 2),
    ('Inicio', 'ingreso.php', 3);


CREATE TABLE departamentos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

## Ej
INSERT INTO departamentos (nombre) VALUES
('bolivar'),
('atlantico'),
('amazonas'),
('antioquia');

CREATE TABLE personas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    edad INT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    departamento_id INT,
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL
);

