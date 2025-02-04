<?php
include 'conexion.php';

// Obtener usuarios con su plan y paquetes contratados
$query = $pdo->query("SELECT u.id, u.nombre, u.email, u.edad, u.plan, u.duracion,
    CASE 
        WHEN u.plan = 'Basico' THEN 9.99 
        WHEN u.plan = 'Estandar' THEN 13.99 
        WHEN u.plan = 'Premium' THEN 17.99 
        ELSE 0 
    END AS precio,
    GROUP_CONCAT(p.nombre SEPARATOR ', ') AS paquetes
    FROM usuarios u
    LEFT JOIN usuario_paquetes up ON u.id = up.usuario_id
    LEFT JOIN paquetes p ON up.paquete_id = p.id
    GROUP BY u.id");
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de paquetes disponibles
$query_paquetes = $pdo->query("SELECT id, nombre FROM paquetes");
$paquetes = $query_paquetes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios Registrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validarFormulario() {
            let edad = document.getElementsByName('edad')[0].value;
            let plan = document.getElementsByName('plan')[0].value;
            let duracion = document.getElementsByName('duracion')[0].value;
            let paquetes = document.getElementsByName('paquetes[]');
            let paquetesSeleccionados = Array.from(paquetes).filter(p => p.checked).map(p => p.value);

            if (edad < 18 && !paquetesSeleccionados.includes('Infantil')) {
                alert('Los menores de 18 años solo pueden contratar el Pack Infantil.');
                return false;
            }
            if (plan === 'Basico' && paquetesSeleccionados.length > 1) {
                alert('El Plan Básico solo permite un paquete adicional.');
                return false;
            }
            if (paquetesSeleccionados.includes('Deporte') && duracion !== 'Anual') {
                alert('El Pack Deporte solo puede ser contratado con la suscripción Anual.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="container mt-5">
    <h1>Registrar Nuevo Usuario</h1>
    <form action="procesar_agregar.php" method="POST" class="mb-4" onsubmit="return validarFormulario()">
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Edad:</label>
            <input type="number" name="edad" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Plan:</label>
            <select name="plan" class="form-select">
                <option value="Basico">Básico</option>
                <option value="Estandar">Estándar</option>
                <option value="Premium">Premium</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Duración:</label>
            <select name="duracion" class="form-select">
                <option value="Mensual">Mensual</option>
                <option value="Anual">Anual</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Paquetes:</label><br>
            <?php foreach ($paquetes as $paquete): ?>
                <input type="checkbox" name="paquetes[]" value="<?= $paquete['nombre'] ?>"> <?= htmlspecialchars($paquete['nombre']) ?><br>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-success">Registrar</button>
    </form>

    <h1>Usuarios Registrados</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Plan</th>
                <th>Duración</th>
                <th>Paquetes</th>
                <th>Precio (€)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= $usuario['edad'] ?></td>
                    <td><?= $usuario['plan'] ?></td>
                    <td><?= $usuario['duracion'] ?></td>
                    <td><?= $usuario['paquetes'] ?: 'Ninguno' ?></td>
                    <td><?= number_format($usuario['precio'], 2) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar.php?id=<?= $usuario['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>