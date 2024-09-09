<?php
require 'conexion_postgres.php';
session_start();

$usuario = pg_escape_string($conexion, $_POST['user']);
$clave = pg_escape_string($conexion, $_POST['pass']);

$query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$clave'";
$consulta = pg_query($conexion, $query);

if (!$consulta) {
    echo "Error en la consulta: " . pg_last_error($conexion);
    exit;
}

$cantidad = pg_num_rows($consulta);

if ($cantidad > 0) {
    $_SESSION['nombre_usuario'] = $usuario;
    header("Location: menu/menu.php");
    exit; 
} else {
    echo "Datos incorrectos!";
}

pg_close($conexion);
?>
