<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

$usuarios = $conn->query("SELECT id, empleado, nombre, apellido_paterno, apellido_materno, correo, rol FROM usuarios");

$filtro_programador = $_GET['programador'] ?? '';
$filtro_complejidad = $_GET['complejidad'] ?? '';
$filtro_estatus = $_GET['estatus'] ?? '';

$programadores = $conn->query("SELECT empleado, nombre, apellido_paterno FROM usuarios WHERE rol = 'programador'");

$condiciones = [];
if ($filtro_programador !== '') {
    $condiciones[] = "u.empleado = '$filtro_programador'";
}
if ($filtro_complejidad !== '') {
    $condiciones[] = "p.complejidad = '$filtro_complejidad'";
}
if ($filtro_estatus !== '') {
    $condiciones[] = "p.estatus = '$filtro_estatus'";
}
$where = count($condiciones) > 0 ? "WHERE " . implode(" AND ", $condiciones) : "";

$proyectos = $conn->query("
    SELECT p.*, 
           u.nombre AS nombre_programador, 
           u.apellido_paterno AS apellido_programador_paterno, 
           u.apellido_materno AS apellido_programador_materno
    FROM proyectos p
    LEFT JOIN usuarios u ON p.asignado_a = u.id
    $where
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #0033A0;
            color: white;
            padding: 20px;
            text-align: center;
        }
        h2, h3 {
            color: #0033A0;
            text-align: center;
        }
        a {
            color: #0033A0;
            font-weight: bold;
            text-decoration: none;
        }
        a.logout {
            float: right;
            margin-top: -60px;
            background-color: #FFD700;
            padding: 8px 12px;
            border-radius: 5px;
        }
        a.logout:hover {
            background-color: #e6c200;
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
        .btn {
            background-color: #FFD700;
            color: #0033A0;
            padding: 6px 12px;
            font-weight: bold;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #e6c200;
        }
        hr {
            margin: 40px 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido Administrador</h1>
    <a class="logout" href="logout.php">Cerrar sesión</a>
</header>

<main>

    <h3>Usuarios registrados</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Empleado</th>
            <th>Nombre completo</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id']; ?></td>
            <td><?= $u['empleado']; ?></td>
            <td><?= ucwords($u['nombre'] . ' ' . $u['apellido_paterno'] . ' ' . $u['apellido_materno']); ?></td>
            <td><?= $u['correo']; ?></td>
            <td><?= $u['rol']; ?></td>
            <td>
                <a href="editar_usuario.php?id=<?= $u['id']; ?>">Editar</a> |
                <a href="eliminar_usuario.php?id=<?= $u['id']; ?>" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a class="btn" href="agregar_usuario.php">➕ Registrar nuevo usuario</a>

    <hr>

    <h3>Proyectos del sistema</h3>

    <form method="GET" action="admin_panel.php" id="filtrosForm" style="margin: 20px auto; background: #fff; padding: 15px 20px; border-radius: 10px; box-shadow: 0 0 5px #ccc; max-width: 750px;">
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between;">
            <div style="flex: 1;">
                <label style="font-weight:bold; font-size: 14px;">Programador:</label>
                <select name="programador" onchange="document.getElementById('filtrosForm').submit();" style="width: 100%; padding: 6px; border-radius: 5px;">
                    <option value="">-- Todos --</option>
                    <?php mysqli_data_seek($programadores, 0); while ($p = $programadores->fetch_assoc()): ?>
                        <option value="<?= $p['empleado']; ?>" <?= $filtro_programador == $p['empleado'] ? 'selected' : '' ?>>
                            <?= ucwords($p['nombre'] . ' ' . $p['apellido_paterno']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div style="flex: 1;">
                <label style="font-weight:bold; font-size: 14px;">Complejidad:</label>
                <select name="complejidad" onchange="document.getElementById('filtrosForm').submit();" style="width: 100%; padding: 6px; border-radius: 5px;">
                    <option value="">-- Todas --</option>
                    <option value="express" <?= $filtro_complejidad == 'express' ? 'selected' : '' ?>>Express</option>
                    <option value="medio" <?= $filtro_complejidad == 'medio' ? 'selected' : '' ?>>Medio</option>
                    <option value="complejo" <?= $filtro_complejidad == 'complejo' ? 'selected' : '' ?>>Complejo</option>
                    <option value="especial" <?= $filtro_complejidad == 'especial' ? 'selected' : '' ?>>Especial</option>
                </select>
            </div>

            <div style="flex: 1;">
                <label style="font-weight:bold; font-size: 14px;">Estatus:</label>
                <select name="estatus" onchange="document.getElementById('filtrosForm').submit();" style="width: 100%; padding: 6px; border-radius: 5px;">
                    <option value="">-- Todos --</option>
                    <option value="activo" <?= $filtro_estatus == 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="pausado" <?= $filtro_estatus == 'pausado' ? 'selected' : '' ?>>Pausado</option>
                    <option value="finalizado" <?= $filtro_estatus == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                </select>
            </div>
        </div>

        <div style="text-align: right; margin-top: 10px;">
            <a href="admin_panel.php" style="background-color: #ccc; color: #0033A0; font-weight: bold; padding: 8px 14px; border-radius: 5px; text-decoration: none;">
                Borrar filtros
            </a>
        </div>
    </form>

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
            <th>Acciones</th>
        </tr>
        <?php while ($p = $proyectos->fetch_assoc()): ?>
        <tr>
            <td><?= $p['id']; ?></td>
            <td><?= $p['nombre']; ?></td>
            <td><?= $p['descripcion']; ?></td>
            <td><?= $p['estatus']; ?></td>
            <td><?= $p['complejidad']; ?></td>
            <td><?= ucwords($p['nombre_programador'] . ' ' . $p['apellido_programador_paterno'] . ' ' . $p['apellido_programador_materno']); ?></td>
            <td><?= $p['comentarios']; ?></td>
            <td><?= $p['fecha_inicio']; ?></td>
            <td><?= $p['fecha_fin']; ?></td>
            <td><a href="editar_proyecto.php?id=<?= $p['id']; ?>">Editar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><br>
    <div style="text-align: center;">
        <a href="estadisticas_admin.php">📊 Ver estadísticas</a>
    </div>

</main>

</body>
</html>
