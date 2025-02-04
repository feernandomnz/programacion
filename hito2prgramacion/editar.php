<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener paquetes
$stmt = $pdo->query("SELECT * FROM paquetes");
$paquetes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener paquetes seleccionados por el usuario
$stmt = $pdo->prepare("SELECT paquete_id FROM usuario_paquetes WHERE usuario_id = ?");
$stmt->execute([$id]);
$paquetesSeleccionados = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $plan = $_POST['plan'];
    $duracion = $_POST['duracion'];
    $paquetesSeleccionados = isset($_POST['paquetes']) ? $_POST['paquetes'] : [];

    // Actualizar usuario
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, edad = ?, plan = ?, duracion = ? WHERE id = ?");
    $stmt->execute([$nombre, $email, $edad, $plan, $duracion, $id]);

    // Actualizar paquetes: eliminar los actuales y agregar los nuevos
    $stmt = $pdo->prepare("DELETE FROM usuario_paquetes WHERE usuario_id = ?");
    $stmt->execute([$id]);

    foreach ($paquetesSeleccionados as $paquete_id) {
        $stmt = $pdo->prepare("INSERT INTO usuario_paquetes (usuario_id, paquete_id) VALUES (?, ?)");
        $stmt->execute([$id, $paquete_id]);
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Editar Usuario</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Edad:</label>
            <input type="number" name="edad" value="<?= $usuario['edad'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Plan:</label>
            <select name="plan" class="form-select">
                <option value="Basico" <?= $usuario['plan'] == 'Basico' ? 'selected' : '' ?>>Básico</option>
                <option value="Estandar" <?= $usuario['plan'] == 'Estandar' ? 'selected' : '' ?>>Estándar</option>
                <option value="Premium" <?= $usuario['plan'] == 'Premium' ? 'selected' : '' ?>>Premium</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Duración:</label>
            <select name="duracion" class="form-select">
                <option value="Mensual" <?= $usuario['duracion'] == 'Mensual' ? 'selected' : '' ?>>Mensual</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Paquetes:</label><br>
            <?php foreach ($paquetes as $paquete): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paquetes[]" value="<?= $paquete['id'] ?>"
                        <?= in_array($paquete['id'], $paquetesSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($paquete['nombre']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</body>
</html>
