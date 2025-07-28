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

$programadores = $conn->query("SELECT id, nombre, apellido_paterno FROM usuarios WHERE rol = 'programador'");

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
    <input type="text" name="nombre" value="<?= $proyecto['nombre'] ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= $proyecto['descripcion'] ?></textarea>

    <label>Estatus:</label>
    <select name="estatus" required>
        <option value="activo" <?= $proyecto['estatus'] == 'activo' ? 'selected' : '' ?>>Activo</option>
        <option value="pausado" <?= $proyecto['estatus'] == 'pausado' ? 'selected' : '' ?>>Pausado</option>
        <option value="finalizado" <?= $proyecto['estatus'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
    </select>

    <label>Complejidad:</label>
    <select name="complejidad" required>
        <option value="express" <?= $proyecto['complejidad'] == 'express' ? 'selected' : '' ?>>Express</option>
        <option value="medio" <?= $proyecto['complejidad'] == 'medio' ? 'selected' : '' ?>>Medio</option>
        <option value="complejo" <?= $proyecto['complejidad'] == 'complejo' ? 'selected' : '' ?>>Complejo</option>
        <option value="especial" <?= $proyecto['complejidad'] == 'especial' ? 'selected' : '' ?>>Especial</option>
    </select>

    <label>Asignado a (programador):</label>
    <select name="asignado_a" required>
        <option value="">-- Selecciona --</option>
        <?php mysqli_data_seek($programadores, 0); while ($p = $programadores->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>" <?= $proyecto['asignado_a'] == $p['id'] ? 'selected' : '' ?>>
                <?= $p['nombre'] . ' ' . $p['apellido_paterno'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Comentarios:</label>
    <textarea name="comentarios"><?= $proyecto['comentarios'] ?></textarea>

    <label>Fecha de inicio:</label>
    <input type="date" name="fecha_inicio" value="<?= $proyecto['fecha_inicio'] ?>" required>

    <label>Fecha de fin:</label>
    <input type="date" name="fecha_fin" value="<?= $proyecto['fecha_fin'] ?>" required>

    <input type="submit" value="Guardar cambios">
</form>

<a class="volver" href="<?= ($_SESSION['rol'] === 'administrador') ? 'admin_panel.php' : 'arquitecto_panel.php' ?>">← Volver al panel</a>

</body>
</html>
