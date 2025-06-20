<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'arquitecto') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if (!isset($_GET['id'])) {
    echo "❌ ID de proyecto no especificado.";
    exit;
}

$id = intval($_GET['id']);

// Eliminar proyecto
$conn->query("DELETE FROM proyectos WHERE id = $id");

// También se eliminarán sus documentos si tienes ON DELETE CASCADE configurado

header("Location: arquitecto_panel.php");
exit;
?>
