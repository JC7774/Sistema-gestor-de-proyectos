<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'programador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $usuario_id = (int) $_SESSION['id'];

    // Verificar que el proyecto pertenezca al programador logueado
    $verificar = $conn->query("SELECT id FROM proyectos WHERE id = $id AND asignado_a = $usuario_id");
    if ($verificar && $verificar->num_rows > 0) {
        $conn->query("UPDATE proyectos SET comentarios = '$comentario' WHERE id = $id");
    }
}

header("Location: programador_panel.php");
exit;
