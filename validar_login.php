<?php
include 'conexion.php';
session_start();

$empleado = $_POST['empleado'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE empleado = '$empleado'";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();

    if ($usuario['password'] == $password) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['empleado'] = $usuario['empleado'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] == 'administrador') {
            header("Location: admin_panel.php");
        } elseif ($usuario['rol'] == 'arquitecto') {
            header("Location: arquitecto_panel.php");
        } elseif ($usuario['rol'] == 'programador') {
            header("Location: programador_panel.php");
        } else {
            echo "⚠️ Rol no reconocido.";
        }
        exit;
    } else {
        echo "⚠️ Contraseña incorrecta.";
    }
} else {
    echo "❌ Usuario no encontrado.";
}
?>
