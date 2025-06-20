<?php
session_start();

if (!isset($_SESSION['empleado']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];


if ($rol === 'arquitecto') {
    header("Location: arquitecto_panel.php");
    exit;
} elseif ($rol === 'programador') {
    header("Location: programador_panel.php");
    exit;
} elseif ($rol === 'administrador') {
    header("Location: admin_panel.php");
    exit;
} else {
    echo "Rol no reconocido.";
}
?>
