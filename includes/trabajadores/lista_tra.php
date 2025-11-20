<?php
require '../../config/config.php';

$stmt = $pdo->query("SELECT * FROM trabajadores ORDER BY id ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de trabajadores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h3>Lista de Trabajadores</h3>

<?php if (isset($_GET['editado'])): ?>
    <div class="alert alert-success d-flex align-items-center">
        <span>Nombre actualizado correctamente.</span>

        <a href="../../index.php" class="btn btn-primary btn-sm ms-auto">
            Volver al inicio
        </a>
    </div>
<?php endif; ?>
<table class="table table-striped table-bordered mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha inicio</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['nombre'] ?></td>
            <td><?= $u['fecha_inicio'] ?></td>
            <td>
                <a href="editar_tra.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">
                    Editar
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
