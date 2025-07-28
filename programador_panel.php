<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'programador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

$usuario_id = (int) ($_SESSION['id'] ?? 0);
$filtro_estatus = $_GET['estatus'] ?? '';

// Obtener nombre del programador
$usuario = $conn->query("SELECT nombre, apellido_paterno FROM usuarios WHERE id = $usuario_id")->fetch_assoc();
$nombre_programador = ucwords($usuario['nombre'] . ' ' . $usuario['apellido_paterno']);

$condicion = "WHERE asignado_a = $usuario_id";
if ($filtro_estatus !== '') {
    $condicion .= " AND estatus = '$filtro_estatus'";
}

$resultado = $conn->query("SELECT * FROM proyectos $condicion");
$estatus_query = $conn->query("SELECT DISTINCT estatus FROM proyectos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Programador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
            margin: 0;
        }
        header {
            background-color: #0033A0;
            color: white;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #0033A0;
            color: white;
        }
        textarea {
            resize: vertical;
        }
        select {
            padding: 6px;
            margin-top: 10px;
            border-radius: 5px;
        }
        button {
            padding: 5px 10px;
            background-color: #FFD700;
            border: none;
            font-weight: bold;
            color: #0033A0;
            border-radius: 5px;
        }
        button:hover {
            background-color: #e6c200;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #FFD700;
            color: #0033A0;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        a:hover {
            background-color: #e6c200;
        }
        form {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido Programador <?= $nombre_programador ?></h1>
</header>

<form method="get">
    <label for="estatus">Estatus:</label>
    <select name="estatus" onchange="this.form.submit()">
        <option value="">Todos</option>
        <?php while ($e = $estatus_query->fetch_assoc()): ?>
            <option value="<?= $e['estatus'] ?>" <?= $filtro_estatus == $e['estatus'] ? 'selected' : '' ?>>
                <?= $e['estatus'] ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>

<p style="text-align: center; margin-top: 10px;">Total de resultados encontrados: <?= $resultado->num_rows ?></p>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Estatus</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Comentarios</th>
        <th>Actualizar</th>
        <th>Archivos</th>
    </tr>
    <?php if ($resultado->num_rows > 0): ?>
        <?php while ($p = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nombre'] ?></td>
            <td><?= $p['estatus'] ?></td>
            <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
            <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
            <td><?= nl2br(htmlspecialchars($p['comentarios'])) ?></td>
            <td>
                <form method="post" action="actualizar_comentarios.php">
                    <textarea name="comentario" rows="3" cols="25"><?= htmlspecialchars($p['comentarios']) ?></textarea>
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <br><button type="submit">Guardar</button>
                </form>
            </td>
            <td>
                <?php
                $archivos = glob("documentos/{$p['id']}/*.pdf");
                foreach ($archivos as $a) {
                    echo "<a href='$a' target='_blank'>" . basename($a) . "</a><br>";
                }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="8">No tienes proyectos asignados.</td></tr>
    <?php endif; ?>
</table>

<div style="text-align: center;">
    <a href="logout.php">Cerrar sesión</a>
</div>

</body>
</html>
