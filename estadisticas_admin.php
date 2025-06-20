<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

$usuarios = $conn->query("SELECT empleado, nombre, apellido_paterno FROM usuarios WHERE rol = 'programador'");
$filtro_empleado = $_GET['empleado'] ?? '';
$filtro_complejidad = $_GET['complejidad'] ?? '';

$condiciones = ["u.rol = 'programador'"];
if ($filtro_empleado !== '') {
    $condiciones[] = "u.empleado = '$filtro_empleado'";
}
if ($filtro_complejidad !== '') {
    $condiciones[] = "p.complejidad = '$filtro_complejidad'";
}

$where = implode(" AND ", $condiciones);

$query = $conn->query("
    SELECT p.estatus, COUNT(*) AS total 
    FROM proyectos p 
    JOIN usuarios u ON p.asignado_a = u.empleado 
    WHERE $where
    GROUP BY p.estatus
");

$labels = [];
$valores = [];
while ($row = $query->fetch_assoc()) {
    $labels[] = $row['estatus'];
    $valores[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas Programadores</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        h2 {
            color: #0033A0;
        }
        .card {
            background: white;
            padding: 20px;
            max-width: 700px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }
        form select {
            padding: 8px;
            margin: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        canvas {
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            background-color: #FFD700;
            color: #0033A0;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a:hover {
            background-color: #e6c200;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Estadísticas de Proyectos Asignados a Programadores</h2>

    <form method="GET" action="estadisticas_admin.php">
        <select name="empleado">
            <option value="">Todos los programadores</option>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <option value="<?php echo $u['empleado']; ?>" <?php if ($filtro_empleado == $u['empleado']) echo 'selected'; ?>>
                    <?php echo $u['nombre'] . ' ' . $u['apellido_paterno']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="complejidad">
            <option value="">Todas las complejidades</option>
            <option value="express" <?php if ($filtro_complejidad == 'express') echo 'selected'; ?>>Express</option>
            <option value="medio" <?php if ($filtro_complejidad == 'medio') echo 'selected'; ?>>Medio</option>
            <option value="complejo" <?php if ($filtro_complejidad == 'complejo') echo 'selected'; ?>>Complejo</option>
            <option value="especial" <?php if ($filtro_complejidad == 'especial') echo 'selected'; ?>>Especial</option>
        </select>

        <input type="submit" value="Filtrar">
    </form>

    <canvas id="graficaEstatus"></canvas>

    <a href="admin_panel.php">← Volver al panel</a>
</div>

<script>
    const ctx = document.getElementById('graficaEstatus').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Proyectos por estatus',
                data: <?php echo json_encode($valores); ?>,
                backgroundColor: ['#FFD700', '#0033A0', '#d9534f']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

</body>
</html>
