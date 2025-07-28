<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

$usuarios = $conn->query("SELECT id, nombre, apellido_paterno FROM usuarios WHERE rol = 'programador'");
$filtro_empleado = $_GET['empleado'] ?? '';

$condicion = '';
if ($filtro_empleado !== '') {
    $condicion = "WHERE asignado_a = '$filtro_empleado'";
}

$query = $conn->query("
    SELECT estatus, COUNT(*) AS total 
    FROM proyectos 
    $condicion
    GROUP BY estatus
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
            text-align: center;
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
    <h2>Estadísticas de Proyectos por Estatus</h2>

    <form method="GET" action="estadisticas_admin.php">
        <select name="empleado">
            <option value="">Todos los programadores</option>
            <?php mysqli_data_seek($usuarios, 0); while ($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id']; ?>" <?= $filtro_empleado == $u['id'] ? 'selected' : '' ?>>
                    <?= ucwords($u['nombre'] . ' ' . $u['apellido_paterno']); ?>
                </option>
            <?php endwhile; ?>
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
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Proyectos por estatus',
                data: <?= json_encode($valores); ?>,
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
