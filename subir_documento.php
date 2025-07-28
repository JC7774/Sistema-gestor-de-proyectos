<?php
session_start();
if (!isset($_SESSION['empleado']) || $_SESSION['rol'] != 'arquitecto') {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if (!isset($_POST['id_proyecto']) || !isset($_FILES['archivo'])) {
    echo "❌ Falta el ID del proyecto o el archivo.";
    exit;
}

$id_proyecto = intval($_POST['id_proyecto']);
$archivo = $_FILES['archivo'];
$nombre_original = basename($archivo['name']);
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

if ($extension !== 'pdf') {
    echo "❌ Solo se permiten archivos PDF.";
    exit;
}

$directorio = "documentos/$id_proyecto/";
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

$nombre_guardado = uniqid() . "_" . $nombre_original;
$ruta_archivo = $directorio . $nombre_guardado;

if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
    $stmt = $conn->prepare("INSERT INTO documentos_proyecto (id_proyecto, nombre_archivo, ruta_archivo) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_proyecto, $nombre_original, $ruta_archivo);
    $stmt->execute();

    echo "✅ Archivo subido correctamente. Redirigiendo...";
    header("refresh:2; url=arquitecto_panel.php");
    exit;
} else {
    echo "❌ Error al mover el archivo. Verifica permisos en la carpeta /documentos.";
}
?>
