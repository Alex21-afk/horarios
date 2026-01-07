<?php
require '../../config/config.php';

$stmt = $pdo->query("SELECT * FROM trabajadores ORDER BY nombre ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Trabajadores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="bg-gradient-custom">

<div class="container mt-5 pb-5">
    <div class="card shadow-lg p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-people-fill me-2"></i>Lista de Trabajadores</h3>
                <p class="text-muted mb-0">Administra la información de los empleados</p>
            </div>
            <a href="../../index.php" class="btn btn-index">
                <i class="bi bi-house-fill me-2"></i>Volver al Inicio
            </a>
        </div>

        <?php if (isset($_GET['editado'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center" role="alert" style="animation: fadeInUp 0.5s ease;">
                <i class="bi bi-check-circle-fill me-3" style="font-size: 1.5rem;"></i>
                <span>✅ Nombre actualizado correctamente</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th width="10%"><i class="bi bi-hash me-2"></i>ID</th>
                <th width="40%"><i class="bi bi-person-fill me-2"></i>Nombre Completo</th>
                <th width="30%"><i class="bi bi-calendar-check me-2"></i>Fecha de Inicio</th>
                <th width="20%" class="text-center"><i class="bi bi-gear-fill me-2"></i>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">No hay trabajadores registrados</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $index => $u): ?>
                <tr style="animation: fadeInUp 0.<?= $index + 3 ?>s ease;">
                    <td><span class="badge bg-secondary rounded-pill"><?= $u['id'] ?></span></td>
                    <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                    <td>
                        <i class="bi bi-calendar3 me-2 text-muted"></i>
                        <?= date('d/m/Y', strtotime($u['fecha_inicio'])) ?>
                    </td>
                    <td class="text-center">
                        <a href="editar_tra.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm" 
                           title="Editar trabajador">
                            <i class="bi bi-pencil-fill me-1"></i>Editar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
