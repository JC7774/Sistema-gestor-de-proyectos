<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_SESSION['empleado'] == $id) {
        echo "<script>alert('No puedes eliminar tu propio usuario.'); window.location.href='admin_panel.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario eliminado exitosamente.'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar usuario.'); window.location.href='admin_panel.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: admin_panel.php");
    exit;
}
?>
