<?php
// Archivo de conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'proyectoGimnasio';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset
$conn->set_charset("utf8mb4");

// Para usar en consultas preparadas
function prepararConsulta($sql) {
    global $conn;
    return $conn->prepare($sql);
}

// Para obtener el último ID insertado
function ultimoId() {
    global $conn;
    return $conn->insert_id;
}

// Para escapar strings (seguridad)
function escapar($string) {
    global $conn;
    return $conn->real_escape_string($string);
}
?>