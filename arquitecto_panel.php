<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'arquitecto') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

$filtro_estatus = $_GET['estatus'] ?? '';
$filtro_programador = $_GET['programador'] ?? '';

$condiciones = [];
if ($filtro_estatus !== '') {
    $condiciones[] = "p.estatus = '$filtro_estatus'";
}
if ($filtro_programador !== '') {
    $condiciones[] = "p.asignado_a = '$filtro_programador'";
}
$condicion = count($condiciones) > 0 ? "WHERE " . implode(" AND ", $condiciones) : "";

$resultado = $conn->query("
    SELECT p.*, CONCAT(u.nombre, ' ', u.apellido_paterno) AS nombre_programador
    FROM proyectos p
    LEFT JOIN usuarios u ON p.asignado_a = u.id
    $condicion
");

$programadores = $conn->query("
    SELECT DISTINCT u.id, u.nombre, u.apellido_paterno
    FROM usuarios u
    INNER JOIN proyectos p ON u.id = p.asignado_a
    WHERE u.rol = 'programador'
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Arquitecto</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #0033A0;
            color: white;
            padding: 20px;
            text-align: center;
        }
        main {
            padding: 20px;
            max-width: 1100px;
            margin: auto;
        }
        h2 {
            color: #0033A0;
        }
        a.logout {
            float: right;
            background-color: #FFD700;
            color: #0033A0;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
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
        .documento-link {
            display: inline-block;
            color: #0033A0;
            text-decoration: underline;
        }
        .editar-btn, .eliminar-btn {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        }
        .editar-btn {
            background-color: #FFD700;
            color: #0033A0;
        }
        .eliminar-btn {
            background-color: #d9534f;
            color: white;
        }
        .eliminar-btn:hover {
            background-color: #c9302c;
        }
        form.subida {
            margin-top: 40px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            max-width: 700px;
        }
        form.subida label {
            font-weight: bold;
            color: #0033A0;
        }
        form.subida input[type="number"],
        form.subida input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form.subida input[type="submit"] {
            background-color: #FFD700;
            color: #0033A0;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido Arquitecto</h1>
    <a class="logout" href="logout.php">Cerrar sesión</a>
</header>

<main>

    <a href="nuevo_proyecto.php" style="display:inline-block; background:#FFD700; color:#0033A0; font-weight:bold; padding:10px 20px; border-radius:5px; text-decoration:none;">➕ Agregar nuevo proyecto</a>

    <h2>Filtrar proyectos</h2>
    <form method="GET" action="arquitecto_panel.php" id="filtrosForm" style="margin-bottom: 30px;">
        <label style="font-weight: bold;">Estatus:</label>
        <select name="estatus" onchange="document.getElementById('filtrosForm').submit();" style="padding: 6px; border-radius: 5px; margin-bottom: 10px;">
            <option value="">-- Todos --</option>
            <option value="activo" <?= $filtro_estatus == 'activo' ? 'selected' : '' ?>>Activo</option>
            <option value="pausado" <?= $filtro_estatus == 'pausado' ? 'selected' : '' ?>>Pausado</option>
            <option value="finalizado" <?= $filtro_estatus == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
        </select>

        <label style="font-weight: bold;">Asignado a (programador):</label>
        <select name="programador" onchange="document.getElementById('filtrosForm').submit();" style="padding: 6px; border-radius: 5px; margin-bottom: 10px;">
            <option value="">-- Todos --</option>
            <?php while ($p = $programadores->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>" <?= $filtro_programador == $p['id'] ? 'selected' : '' ?>>
                    <?= $p['nombre'] . ' ' . $p['apellido_paterno'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div style="text-align: right;">
            <a href="arquitecto_panel.php" style="background-color: #ccc; color: #0033A0; font-weight: bold; padding: 6px 12px; border-radius: 5px; text-decoration: none;">
                Borrar filtros
            </a>
        </div>
    </form>

    <h2>Lista de proyectos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estatus</th>
            <th>Complejidad</th>
            <th>Asignado a</th>
            <th>Comentarios</th>
            <th>Fecha inicio</th>
            <th>Fecha fin</th>
            <th>Documentos</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <?php $docs = $conn->query("SELECT id, nombre_archivo, ruta_archivo FROM documentos_proyecto WHERE id_proyecto = " . $fila['id']); ?>
            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= $fila['nombre'] ?></td>
                <td><?= $fila['descripcion'] ?></td>
                <td><?= $fila['estatus'] ?></td>
                <td><?= $fila['complejidad'] ?></td>
                <td><?= $fila['nombre_programador'] ?? 'Sin asignar' ?></td>
                <td><?= $fila['comentarios'] ?></td>
                <td><?= $fila['fecha_inicio'] ?></td>
                <td><?= $fila['fecha_fin'] ?></td>
                <td>
                    <?php while ($doc = $docs->fetch_assoc()): ?>
                        <div style="margin-bottom: 8px;">
                            <a class="documento-link" href="<?= $doc['ruta_archivo'] ?>" target="_blank"><?= $doc['nombre_archivo'] ?></a>
                            <a class="eliminar-btn" href="eliminar_documento.php?id=<?= $doc['id'] ?>" onclick="return confirm('¿Eliminar este documento?');">❌</a>
                        </div>
                    <?php endwhile; ?>
                </td>
                <td>
                    <a class="editar-btn" href="editar_proyecto.php?id=<?= $fila['id'] ?>">Editar</a><br><br>
                    <a class="eliminar-btn" href="eliminar_proyecto.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este proyecto?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Subir documento a un proyecto</h2>
    <form method="POST" action="subir_documento.php" enctype="multipart/form-data" class="subida">
        <label>ID del proyecto:</label>
        <input type="number" name="id_proyecto" required>

        <label>Archivo PDF:</label>
        <input type="file" name="archivo" accept="application/pdf" required>

        <input type="submit" value="Subir documento">
    </form>

</main>
</body>
</html>
