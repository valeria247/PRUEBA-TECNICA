<?php
include('../conexion_postgres.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];

    // Actualizar un departamento
    $query = "UPDATE departamentos SET nombre = $1 WHERE id = $2";
    $result = pg_prepare($conexion, "update_departamento", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "update_departamento", array($nombre, $id));
        if ($result) {
            // Redirigir a departamentos.php después de la actualización
            header('Location: ../menu/departamentos.php');
            exit(); 
        } else {
            $message = "Error al actualizar departamento: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Consultar el departamento actual
    $query = "SELECT * FROM departamentos WHERE id = $1";
    $result = pg_prepare($conexion, "select_departamento", $query);
    $result = pg_execute($conexion, "select_departamento", array($id));
    
    if ($result) {
        if (pg_num_rows($result) > 0) {
            $departamento = pg_fetch_assoc($result);
        } else {
            die("Departamento no encontrado.");
        }
    } else {
        die("Error en la consulta: " . pg_last_error($conexion));
    }
} else {
    die("ID de departamento no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Departamento</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Editar Departamento</h1>
        <?php if (isset($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form action="../menu/edit_departamento.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($departamento['id']); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($departamento['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizar Departamento">
            </div>
        </form>
    </div>
</body>
</html>
