<?php
session_start();
$conn = new mysqli("localhost", "root", "1234", "gestion_tareas");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejo de acciones (registro, login, logout, agregar tarea)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["accion"])) {
        switch ($_POST["accion"]) {
            case "registrar":
                $nombre = trim($_POST["nombre"]);
                $correo = trim($_POST["correo"]);
                $contraseña = password_hash($_POST["contraseña"], PASSWORD_BCRYPT);

                // Verificar si el correo ya existe
                $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $_SESSION["error"] = "El correo ya está registrado.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, contraseña) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $nombre, $correo, $contraseña);
                    $stmt->execute();
                    $_SESSION["mensaje"] = "Registro exitoso. Inicia sesión.";
                }
                break;

            case "iniciar_sesion":
                $correo = trim($_POST["correo"]);
                $contraseña = $_POST["contraseña"];

                $stmt = $conn->prepare("SELECT id, nombre_usuario, contraseña FROM usuarios WHERE correo = ?");
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($usuario = $result->fetch_assoc()) {
                    if (password_verify($contraseña, $usuario["contraseña"])) {
                        $_SESSION["userid"] = $usuario["id"];
                        $_SESSION["username"] = $usuario["nombre_usuario"];
                    } else {
                        $_SESSION["error"] = "Contraseña incorrecta.";
                    }
                } else {
                    $_SESSION["error"] = "Usuario no encontrado.";
                }
                break;

            case "cerrar_sesion":
                session_destroy();
                header("Location: index.php");
                exit();

            case "agregar_tarea":
                if (isset($_SESSION["userid"]) && !empty($_POST["tarea"])) {
                    $tarea = trim($_POST["tarea"]);
                    $stmt = $conn->prepare("INSERT INTO tareas (usuario_id, descripcion) VALUES (?, ?)");
                    $stmt->bind_param("is", $_SESSION["userid"], $tarea);
                    $stmt->execute();
                }
                break;
        }
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php if (isset($_SESSION["mensaje"])): ?>
    <p style="color: green;"><?php echo $_SESSION["mensaje"]; unset($_SESSION["mensaje"]); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION["error"])): ?>
    <p style="color: red;"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION["userid"])): ?>
    <h1>Bienvenido, <?php echo $_SESSION["username"]; ?></h1>

    <form method="POST">
        <input type="text" name="tarea" placeholder="Nueva tarea" required>
        <button type="submit" name="accion" value="agregar_tarea">Agregar Tarea</button>
    </form>

    <h2>Mis Tareas</h2>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT id, descripcion FROM tareas WHERE usuario_id = ?");
        $stmt->bind_param("i", $_SESSION["userid"]);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($tarea = $result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($tarea["descripcion"]); ?></li>
        <?php endwhile; ?>
    </ul>

    <form method="POST">
        <button type="submit" name="accion" value="cerrar_sesion">Cerrar Sesión</button>
    </form>

<?php else: ?>

    <h1>Iniciar Sesión</h1>
    <form method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit" name="accion" value="iniciar_sesion">Iniciar Sesión</button>
    </form>

    <h1>Registrarse</h1>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre de usuario" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit" name="accion" value="registrar">Registrarse</button>
    </form>

<?php endif; ?>

</body>
</html>
