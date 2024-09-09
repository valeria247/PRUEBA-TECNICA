<?php
include('../conexion_postgres.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];
    $departamento_id = !empty($_POST['departamento_id']) ? $_POST['departamento_id'] : null;

    // Agregar una persona
    $query = "INSERT INTO personas (nombre, edad, direccion, departamento_id) VALUES ($1, $2, $3, $4)";
    $result = pg_prepare($conexion, "insert_persona", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "insert_persona", array($nombre, $edad, $direccion, $departamento_id));
        if ($result) {
            $message = "Persona agregada exitosamente.";
        } else {
            $message = "Error al agregar persona: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}


// Eliminar
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM personas WHERE id = $1";
    $result = pg_prepare($conexion, "delete_persona", $query);
    
    if ($result) {
        $result = pg_execute($conexion, "delete_persona", array($delete_id));
        if ($result) {
            $message = "Persona eliminada exitosamente.";
        } else {
            $message = "Error al eliminar persona: " . pg_last_error($conexion);
        }
    } else {
        $message = "Error al preparar la consulta: " . pg_last_error($conexion);
    }
}

// Consultar las personas
$query = "SELECT personas.id, personas.nombre, personas.edad, personas.direccion, departamentos.nombre AS departamento 
          FROM personas
          LEFT JOIN departamentos ON personas.departamento_id = departamentos.id";
$result = pg_query($conexion, $query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Personas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Personas</h1>
        <?php if (isset($message)) { ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>
        <form action="personas.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required><br>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required><br>

            <label for="departamento">Departamento:</label>
            <select id="departamento" name="departamento_id" required>
                <option value="">Seleccione un departamento</option>
                <?php
                // Departamentos del select
                $query_departamentos = "SELECT id, nombre FROM departamentos";
                $result_departamentos = pg_query($conexion, $query_departamentos);

                while ($departamento = pg_fetch_assoc($result_departamentos)) {
                    echo "<option value='" . htmlspecialchars($departamento['id']) . "'>" . htmlspecialchars($departamento['nombre']) . "</option>";
                }
                ?>
            </select><br>

            <input type="submit" value="Agregar Persona">
        </form>

        <h2>Lista de Personas</h2>
        <?php
        if ($result) {
            if (pg_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Dirección</th>
                            <th>Departamento</th>
                            <th>Opciones</th>
                        </tr>";
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>" . htmlspecialchars($row['edad']) . "</td>
                            <td>" . htmlspecialchars($row['direccion']) . "</td>
                            <td>" . htmlspecialchars($row['departamento']) . "</td>
                            <td>
                                <a href='edit_persona.php?id=" . htmlspecialchars($row['id']) . "'>Editar</a> | 
                                <a href='personas.php?delete_id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar esta persona?');\">Eliminar</a>
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay personas registradas.</p>";
            }
        } else {
            echo "<p>Error en la consulta: " . pg_last_error($conexion) . "</p>";
        }
        ?>
    </div>
</body>
</html>

