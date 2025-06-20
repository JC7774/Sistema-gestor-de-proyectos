<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "gestor_proyectos";
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>