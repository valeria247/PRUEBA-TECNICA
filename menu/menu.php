<?php
session_start();
$usuario = $_SESSION['nombre_usuario'];
include('../conexion_postgres.php'); 

// Consultar los elementos del menú desde la base de datos
$query = "SELECT nombre, url FROM menu ORDER BY orden";
$result = pg_query($conexion, $query);

if (!$result) {
    echo "Error en la consulta: " . pg_last_error($conexion);
    exit;
}

// Guardar los elementos del menú en un array
$items = [];
while ($menu_item = pg_fetch_assoc($result)) {
    $items[] = $menu_item;
}

pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Menú</title>
</head>
<body>

<!-- Contenedor del mensaje de bienvenida -->
<div class="welcome-container">
    <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1>
</div>

<!-- Contenedor del menú -->
<div class="menu-container">
    <h2>Menú</h2>
    <div class="menu-buttons">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <a href="<?php echo htmlspecialchars($item['url']); ?>"><?php echo htmlspecialchars($item['nombre']); ?></a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay elementos en el menú.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
