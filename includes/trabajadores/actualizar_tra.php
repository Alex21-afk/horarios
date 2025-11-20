<?php
require '../../config/config.php';
$id = $_POST['id'];
$nombre = $_POST['nombre'];

$stmt = $pdo->prepare("UPDATE trabajadores SET nombre = ? WHERE id = ?");
$resultado = $stmt->execute([$nombre, $id]);

if ($resultado) {
    header("Location: lista_tra.php?editado=1");
    exit();
} else {
    echo "Error al actualizar el nombre.";
}
