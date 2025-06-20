<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['empleado']) || !in_array($_SESSION['rol'], ['administrador', 'arquitecto'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de proyecto no válido.";
    exit;
}

$resultado = $conn->query("SELECT * FROM proyectos WHERE id = $id");
if ($resultado->num_rows === 0) {
    echo "Proyecto no encontrado.";
    exit;
}
$proyecto = $resultado->fetch_assoc();

$programadores = $conn->query("SELECT empleado, nombre, apellido_paterno FROM usuarios WHERE rol = 'programador'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estatus = $_POST['estatus'];
    $complejidad = $_POST['complejidad'];
    $asignado_a = $_POST['asignado_a'];
    $comentarios = $_POST['comentarios'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "UPDATE proyectos SET 
                nombre = '$nombre',
                descripcion = '$descripcion',
                estatus = '$estatus',
                complejidad = '$complejidad',
                asignado_a = '$asignado_a',
                comentarios = '$comentarios',
                fecha_inicio = '$fecha_inicio',
                fecha_fin = '$fecha_fin'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $redirect = ($_SESSION['rol'] === 'administrador') ? 'admin_panel.php' : 'arquitecto_panel.php';
        header("Location: $redirect");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        form {
            background-color: white;
            max-width: 600px;
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

        input, select, textarea {
            width: 100%;
            padding: 8px;
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

<h2>Editar Proyecto</h2>

<form method="POST">
    <label>Nombre del proyecto:</label>
    <input type="text" name="nombre" value="<?php echo $proyecto['nombre']; ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?php echo $proyecto['descripcion']; ?></textarea>

    <label>Estatus:</label>
    <select name="estatus" required>
        <option value="activo" <?php if ($proyecto['estatus'] == 'activo') echo 'selected'; ?>>Activo</option>
        <option value="pausado" <?php if ($proyecto['estatus'] == 'pausado') echo 'selected'; ?>>Pausado</option>
        <option value="finalizado" <?php if ($proyecto['estatus'] == 'finalizado') echo 'selected'; ?>>Finalizado</option>
    </select>

    <label>Complejidad:</label>
    <select name="complejidad" required>
        <option value="express" <?php if ($proyecto['complejidad'] == 'express') echo 'selected'; ?>>Express</option>
        <option value="medio" <?php if ($proyecto['complejidad'] == 'medio') echo 'selected'; ?>>Medio</option>
        <option value="complejo" <?php if ($proyecto['complejidad'] == 'complejo') echo 'selected'; ?>>Complejo</option>
        <option value="especial" <?php if ($proyecto['complejidad'] == 'especial') echo 'selected'; ?>>Especial</option>
    </select>

    <label>Asignado a (programador):</label>
    <select name="asignado_a" required>
        <option value="">-- Selecciona --</option>
        <?php mysqli_data_seek($programadores, 0); while ($p = $programadores->fetch_assoc()): ?>
            <option value="<?php echo $p['empleado']; ?>" <?php if ($proyecto['asignado_a'] == $p['empleado']) echo 'selected'; ?>>
                <?php echo $p['nombre'] . ' ' . $p['apellido_paterno']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Comentarios:</label>
    <textarea name="comentarios"><?php echo $proyecto['comentarios']; ?></textarea>

    <label>Fecha de inicio:</label>
    <input type="date" name="fecha_inicio" value="<?php echo $proyecto['fecha_inicio']; ?>" required>

    <label>Fecha de fin:</label>
    <input type="date" name="fecha_fin" value="<?php echo $proyecto['fecha_fin']; ?>" required>

    <input type="submit" value="Guardar cambios">
</form>

<a class="volver" href="<?php echo ($_SESSION['rol'] === 'administrador') ? 'admin_panel.php' : 'arquitecto_panel.php'; ?>">← Volver al panel</a>

</body>
</html>
