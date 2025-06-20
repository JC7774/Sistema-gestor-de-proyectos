<?php
include 'conexion.php';
session_start();

$empleado = $_POST['empleado'];
$password = $_POST['password'];

// Consulta a la base de datos
$sql = "SELECT * FROM usuarios WHERE empleado = '$empleado'";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();

    // Validar la contraseña (en este caso sin hash)
    if ($usuario['password'] == $password) {
        $_SESSION['empleado'] = $usuario['empleado'];
        $_SESSION['rol'] = $usuario['rol'];
        header("Location: index.php");
        exit;
    } else {
        echo "⚠️ Contraseña incorrecta.";
    }
} else {
    echo "❌ Usuario no encontrado.";
}
?>