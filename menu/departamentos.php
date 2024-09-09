<?php
include('../conexion_postgres.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    // Agregar un departamento
    $query = "INSERT INTO departamentos (nombre) VALUES ($1)";
    $result = pg_prepare($conexion, "insert_departamento", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "insert_departamento", array($nombre));
        if ($result) {
            $message = "Departamento agregado exitosamente.";
        } else {
            $message = "Error al agregar departamento: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}

// Eliminar un registro
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM departamentos WHERE id = $1";
    $result = pg_prepare($conexion, "delete_departamento", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "delete_departamento", array($delete_id));
        if ($result) {
            $message = "Departamento eliminado exitosamente.";
        } else {
            $message = "Error al eliminar departamento: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}

// Consultar todos los departamentos
$query = "SELECT * FROM departamentos";
$result = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Departamentos</title>
    <link rel="stylesheet" href="../css/estilos.css"> 
</head>
<body>
    <div class="container">
        <h1>Gestión de Departamentos</h1>
        <?php if (isset($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form action="../menu/departamentos.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Agregar Departamento">
            </div>
        </form>

        <h2>Lista de Departamentos</h2>
        <?php
        if ($result) {
            if (pg_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Opciones</th>
                        </tr>";
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>
                                <a href='../menu/edit_departamento.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Editar</a> | 
                                <a href='../menu/departamentos.php?delete_id=" . htmlspecialchars($row['id']) . "' class='delete-btn' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este departamento?');\">Eliminar</a>
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay departamentos registrados.</p>";
            }
        } else {
            echo "<p>Error en la consulta: " . pg_last_error($conexion) . "</p>";
        }

        pg_close($conexion);
        ?>
    </div>
</body>
</html>
