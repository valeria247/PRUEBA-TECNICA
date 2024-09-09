<?php
include('../conexion_postgres.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];

    // Editar, UPDATE
    $query = "UPDATE personas SET nombre = $1, edad = $2, direccion = $3 WHERE id = $4";
    $result = pg_prepare($conexion, "update_persona", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "update_persona", array($nombre, $edad, $direccion, $id));
        if ($result) {
            header("Location: ../menu/personas.php");
            exit();
        } else {
            $message = "Error al actualizar persona: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}

// Selecciona la persona que desea editar
$id = intval($_GET['id']);
$query = "SELECT * FROM personas WHERE id = $1";
$result = pg_prepare($conexion, "get_persona", $query);
$result = pg_execute($conexion, "get_persona", array($id));

$persona = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Persona</title>
    <link rel="stylesheet" href="../css/estilos.css"> 
</head>
<body>
    <div class="container">
        <h1>Editar Persona</h1>
        <?php if (isset($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form action="../menu/edit_persona.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($persona['id']); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($persona['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($persona['edad']); ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($persona['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizar Persona">
            </div>
        </form>
    </div>
</body>
</html>
