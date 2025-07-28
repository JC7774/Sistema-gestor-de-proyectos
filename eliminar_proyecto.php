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


$conn->query("DELETE FROM proyectos WHERE id = $id");



header("Location: arquitecto_panel.php");
exit;
?>
