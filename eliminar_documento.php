<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'arquitecto') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ ID de documento no válido.";
    exit;
}

$id_documento = intval($_GET['id']);

// Verificar existencia del documento
$stmt = $conn->prepare("SELECT ruta_archivo FROM documentos_proyecto WHERE id = ?");
$stmt->bind_param("i", $id_documento);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "❌ El documento no existe en la base de datos.";
    exit;
}

$doc = $result->fetch_assoc();
$ruta = $doc['ruta_archivo'];

// Borrar archivo del servidor si existe
if (file_exists($ruta)) {
    if (!unlink($ruta)) {
        echo "❌ No se pudo eliminar el archivo del servidor.";
        exit;
    }
}

// Eliminar registro de la base de datos
$stmtDel = $conn->prepare("DELETE FROM documentos_proyecto WHERE id = ?");
$stmtDel->bind_param("i", $id_documento);
$stmtDel->execute();

header("Location: arquitecto_panel.php");
exit;
?>
