<?php
include 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar primero las referencias en la tabla intermedia usuario_paquetes
    $stmt = $pdo->prepare("DELETE FROM usuario_paquetes WHERE usuario_id = ?");
    $stmt->execute([$id]);

    // Luego eliminar el usuario
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>
