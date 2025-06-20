<?php
session_start();
if (isset($_SESSION['empleado'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Proyectos - Login</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: sans-serif;
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

        .olvide {
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }

        .olvide a {
            color: #0033A0;
            text-decoration: none;
        }

        .olvide a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Gestor de Proyectos</h2>

<form method="POST" action="validar_login.php">
    <label>Número de empleado:</label>
    <input type="text" name="empleado" required maxlength="9">

    <label>Contraseña:</label>
    <input type="password" name="password" required>

    <input type="submit" value="Iniciar sesión">
</form>

<div class="olvide">
    <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
</div>

</body>
</html>
