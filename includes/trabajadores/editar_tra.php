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
<html>
<head>
    <title>Editar trabajador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h3>Editar nombre del trabajador</h3>

<form action="actualizar_tra.php" method="POST" class="mt-4">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"
               class="form-control" required>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="lista_tra.php" class="btn btn-secondary">Regresar</a>
</form>

</body>
</html>
