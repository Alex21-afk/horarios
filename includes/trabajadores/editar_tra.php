<?php
require '../../config/config.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM trabajadores WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trabajador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="bg-gradient-custom">

<div class="container mt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-fill-gear" style="font-size: 3rem; color: var(--warning);"></i>
                    <h3 class="mt-3">Editar Trabajador</h3>
                    <p class="text-muted">Actualiza la información del empleado</p>
                </div>

                <form action="actualizar_tra.php" method="POST" class="mt-4">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-person-badge me-2"></i>ID del Trabajador</label>
                        <input type="text" class="form-control" value="<?= $usuario['id'] ?>" disabled>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-person-fill me-2"></i>Nombre Completo</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"
                               class="form-control" required placeholder="Ingrese el nombre completo">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Este campo es obligatorio</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-calendar-event me-2"></i>Fecha de Inicio</label>
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($usuario['fecha_inicio'])) ?>" disabled>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-2"></i>Guardar Cambios
                        </button>
                        <a href="lista_tra.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Regresar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
