<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'programador') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel del Programador</title>
</head>
<body>
    <h2>Bienvenido Programador</h2>
    <a href="logout.php">Cerrar sesión</a>
    <p>Aquí puedes ver tus proyectos asignados y actualizar tus avances.</p>
</body>
</html>
