<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $plan = $_POST['plan'];
    $duracion = $_POST['duracion'];
    $precio = 0.00; // Asegurar que hay un valor por defecto

    // Verificar si el email ya está registrado
    $sql_check = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email]);
    if ($stmt_check->fetchColumn() > 0) {
        die("Error: El correo electrónico ya está registrado.");
    }

    // Insertar usuario
    $sql = "INSERT INTO usuarios (nombre, email, edad, plan, duracion, precio) 
            VALUES (:nombre, :email, :edad, :plan, :duracion, :precio)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'email' => $email,
        'edad' => $edad,
        'plan' => $plan,
        'duracion' => $duracion,
        'precio' => $precio
    ]);

    // Redirigir a index.php
    header("Location: index.php");
    exit();
}
?>