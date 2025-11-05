<?php include 'includes/header.php'; ?>
<?php require 'config/config.php'; ?>

<?php
function turnoTrabajador($fechaConsulta, $fechaInicio) {
    $turnos = [
        "Turno Tarde (Lun-Sáb 3pm-11pm)",
        "Mañana Lun-Sáb (7am-4pm)",
        "Mañana Lun-Vie (6:45am-5pm)"
    ];

    $inicio = new DateTime($fechaInicio);
    $consulta = new DateTime($fechaConsulta);

    $dias = $inicio->diff($consulta)->days;
    $semana = floor($dias / 7);
    $turnoIndex = $semana % 3;

    return $turnos[$turnoIndex];
}
?>

<div class="card shadow p-4">
    <h3 class="mb-4 text-center">Consulta de Turno</h3>

    <form method="POST" class="row g-3">

        <!-- SELECT TRABAJADOR -->
        <div class="col-md-6">
            <label class="form-label">Trabajador</label>
            <select class="form-select" name="trabajador_id" required>
                <option value="">Seleccionar...</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM trabajadores");
                while($row = $stmt->fetch()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- FECHA -->
        <div class="col-md-6">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-index w-100">Consultar</button>
        </div>

    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['trabajador_id'];
        $fecha = $_POST['fecha'];

        $stmt = $pdo->prepare("SELECT * FROM trabajadores WHERE id = ?");
        $stmt->execute([$id]);
        $trabajador = $stmt->fetch();

        if ($trabajador) {
            $turno = turnoTrabajador($fecha, $trabajador['fecha_inicio']);

            echo "
            <div class='alert alert-info mt-4'>
                <b>{$trabajador['nombre']}</b> estará el día <b>$fecha</b> en:<br>
                <h5 class='mt-2'>$turno</h5>
            </div>";
        } else {
            echo "<div class='alert alert-danger mt-4'>Trabajador no encontrado</div>";
        }
    }
    ?>

</div>

<?php include 'includes/footer.php'; ?>
