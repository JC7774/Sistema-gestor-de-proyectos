<?php
session_start();         // Inicia la sesión si no estaba activa
session_destroy();       // Destruye toda la información de sesión
header("Location: login.php");  // Redirige al login
exit;
