<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['empleado']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no válido.";
    exit;
}

$resultado = $conn->query("SELECT * FROM usuarios WHERE id = $id");
if ($resultado->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit;
}
$usuario = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET
                nombre = '$nombre',
                apellido_paterno = '$apellido_paterno',
                apellido_materno = '$apellido_materno',
                correo = '$correo',
                rol = '$rol'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        form {
            background-color: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #0033A0;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #0033A0;
            display: block;
            margin-top: 15px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            margin-top: 25px;
            background-color: #FFD700;
            color: #0033A0;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #e6c200;
        }

        .volver {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #0033A0;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Editar Usuario</h2>

<form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

    <label>Apellido Paterno:</label>
    <input type="text" name="apellido_paterno" value="<?php echo $usuario['apellido_paterno']; ?>" required>

    <label>Apellido Materno:</label>
    <input type="text" name="apellido_materno" value="<?php echo $usuario['apellido_materno']; ?>" required>

    <label>Correo:</label>
    <input type="email" name="correo" value="<?php echo $usuario['correo']; ?>" required>

    <label>Rol:</label>
    <select name="rol" required>
        <option value="administrador" <?php if ($usuario['rol'] == 'administrador') echo 'selected'; ?>>Administrador</option>
        <option value="arquitecto" <?php if ($usuario['rol'] == 'arquitecto') echo 'selected'; ?>>Arquitecto</option>
        <option value="programador" <?php if ($usuario['rol'] == 'programador') echo 'selected'; ?>>Programador</option>
    </select>

    <input type="submit" value="Actualizar usuario">
</form>

<a class="volver" href="admin_panel.php">← Volver al panel</a>

</body>
</html>
