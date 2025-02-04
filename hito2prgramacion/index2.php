<?php
include 'conexion.php';

// Insertar usuario
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $plan = $_POST['plan'];
    $duracion = $_POST['duracion'];

    $sql = "INSERT INTO usuarios (nombre, email, edad, plan, duracion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$nombre, $email, $edad, $plan, $duracion])) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al registrar usuario.";
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$id])) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al eliminar usuario.";
    }
}

// Editar usuario
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $plan = $_POST['plan'];
    $duracion = $_POST['duracion'];

    $sql = "UPDATE usuarios SET nombre=?, email=?, edad=?, plan=?, duracion=? WHERE id=?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$nombre, $email, $edad, $plan, $duracion, $id])) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar usuario.";
    }
}

// Obtener lista de usuarios
$result = $pdo->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Registrar Usuario</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $_GET['editar'] ?? '' ?>">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Edad: <input type="number" name="edad" required></label><br>
    <label>Plan:
        <select name="plan">
            <option value="Basico">Básico</option>
            <option value="Estandar">Estándar</option>
            <option value="Premium">Premium</option>
        </select>
    </label><br>
    <label>Duración:
        <select name="duracion">
            <option value="Mensual">Mensual</option>
            <option value="Anual">Anual</option>
        </select>
    </label><br>
    <button type="submit" name="<?= isset($_GET['editar']) ? 'editar' : 'registrar' ?>">
        <?= isset($_GET['editar']) ? 'Actualizar' : 'Registrar' ?>
    </button>
</form>

<h2>Usuarios Registrados</h2>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Edad</th>
        <th>Plan</th>
        <th>Duración</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['edad'] ?></td>
            <td><?= $row['plan'] ?></td>
            <td><?= $row['duracion'] ?></td>
            <td>
                <a href="index2.php?editar=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="index2.php?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>