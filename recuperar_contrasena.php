<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado = $_POST['empleado'];
    $nueva = $_POST['nueva_contrasena'];

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $nueva)) {
        echo "❌ La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.";
        exit;
    }

    $resultado = $conn->query("SELECT * FROM usuarios WHERE empleado = '$empleado'");
    if ($resultado->num_rows == 0) {
        echo "❌ Empleado no encontrado.";
        exit;
    }

    $conn->query("UPDATE usuarios SET password = '$nueva' WHERE empleado = '$empleado'");
    echo "✅ Contraseña actualizada correctamente.";
    header("refresh:2; url=login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 80px;
        }

        h2 {
            color: #0033A0;
        }

        form {
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 30px;
            width: 300px;
            margin: auto;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #FFD700;
            color: #0033A0;
            font-weight: bold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #e6c200;
        }

        .volver {
            display: block;
            margin-top: 20px;
            color: #0033A0;
            text-decoration: none;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Recuperar contraseña</h2>

<form method="POST" action="">
    <label>Número de empleado:</label>
    <input type="text" name="empleado" required maxlength="9">

    <label>Nueva contraseña:</label>
    <input type="password" name="nueva_contrasena" required>

    <input type="submit" value="Actualizar contraseña">
</form>

<a class="volver" href="login.php">← Volver al login</a>

</body>
</html>
