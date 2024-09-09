<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <form action="sesion.php" method="POST">
            <label for="user">Usuario:</label>
            <input type="text" id="user" name="user" placeholder="Ingrese su usuario" required>
            <br>
            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" placeholder="Ingrese su contraseña" required>
            <br>
            <button type="submit" class="boton-estilo">Ingresar</button>
        </form>
    </div>
</body>
</html>
