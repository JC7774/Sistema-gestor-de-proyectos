<?php
include 'conexion.php';

function validarPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado = $_POST['empleado'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    if (!preg_match('/^\d{8}$/', $empleado)) {
        $error = "El número de empleado debe tener exactamente 8 dígitos numéricos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif (!validarPassword($password)) {
        $error = "La contraseña debe tener al menos 12 caracteres, incluyendo mayúscula, minúscula, número y símbolo.";
    } else {
        $verificar = $conn->query("SELECT * FROM usuarios WHERE empleado = '$empleado'");
        if ($verificar->num_rows > 0) {
            $error = "Ya existe un usuario con ese número de empleado.";
        } else {
            $sql = "INSERT INTO usuarios (empleado, nombre, apellido_paterno, apellido_materno, correo, password, rol)
                    VALUES ('$empleado', '$nombre', '$apellido_paterno', '$apellido_materno', '$correo', '$password', '$rol')";
            if ($conn->query($sql) === TRUE) {
                header("Location: admin_panel.php");
                exit;
            } else {
                $error = "Error al guardar: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            margin: 0;
            padding: 40px;
        }
        h2 {
            color: #0033A0;
        }
        form {
            background-color: white;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
            color: #0033A0;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #FFD700;
            color: #0033A0;
            font-weight: bold;
            border: none;
            padding: 12px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #e6c200;
        }
        .error {
            background-color: #f8d7da;
            padding: 10px;
            color: #721c24;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0033A0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Registro de nuevo usuario</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label>Número de empleado:</label>
    <input type="text" name="empleado" required pattern="^\d{8}$">

    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Apellido paterno:</label>
    <input type="text" name="apellido_paterno" required>

    <label>Apellido materno:</label>
    <input type="text" name="apellido_materno" required>

    <label>Correo:</label>
    <input type="email" name="correo" required>

    <label>Contraseña:</label>
    <input type="password" name="password" required
           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,}$"
           title="Debe tener al menos 12 caracteres, una mayúscula, una minúscula, un número y un carácter especial">

    <label>Rol:</label>
    <select name="rol" required>
        <option value="administrador">Administrador</option>
        <option value="arquitecto">Arquitecto</option>
        <option value="programador">Programador</option>
    </select>

    <input type="submit" value="Registrar usuario">
</form>

<a class="volver" href="admin_panel.php">← Volver al panel</a>

</body>
</html>
