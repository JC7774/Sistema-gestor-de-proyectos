<?php
include 'conexion.php';

// Obtener lista de programadores (nombre completo)
$programadores = $conn->query("SELECT CONCAT(nombre, ' ', apellido_paterno) AS nombre_completo FROM usuarios WHERE rol = 'programador'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estatus = $_POST['estatus'];
    $complejidad = $_POST['complejidad'];
    $asignado_a = $_POST['asignado_a'];
    $comentarios = $_POST['comentarios'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "INSERT INTO proyectos (nombre, descripcion, estatus, complejidad, asignado_a, comentarios, fecha_inicio, fecha_fin)
            VALUES ('$nombre', '$descripcion', '$estatus', '$complejidad', '$asignado_a', '$comentarios', '$fecha_inicio', '$fecha_fin')";

    if ($conn->query($sql) === TRUE) {
        header("Location: arquitecto_panel.php");
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
    <title>Agregar Proyecto</title>
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

<h2>Agregar nuevo proyecto</h2>

<form method="POST">
    <label>Nombre del proyecto:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea>

    <label>Estatus:</label>
    <select name="estatus" required>
        <option value="activo">Activo</option>
        <option value="pausado">Pausado</option>
        <option value="finalizado">Finalizado</option>
    </select>

    <label>Complejidad:</label>
    <select name="complejidad" required>
        <option value="express">Express</option>
        <option value="medio" selected>Medio</option>
        <option value="complejo">Complejo</option>
        <option value="especial">Especial</option>
    </select>

    <label>Asignado a (programador):</label>
    <select name="asignado_a" required>
        <option value="">-- Selecciona un programador --</option>
        <?php while ($p = $programadores->fetch_assoc()): ?>
            <option value="<?php echo $p['nombre_completo']; ?>">
                <?php echo $p['nombre_completo']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Comentarios:</label>
    <textarea name="comentarios"></textarea>

    <label>Fecha de inicio:</label>
    <input type="date" name="fecha_inicio" required>

    <label>Fecha de fin:</label>
    <input type="date" name="fecha_fin" required>

    <input type="submit" value="Guardar proyecto">
</form>

<a class="volver" href="arquitecto_panel.php">← Volver al panel</a>

</body>
</html>
