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
            <select class="form-select input-index " name="trabajador_id" required>
                <option value="">Seleccionar...</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM trabajadores");
                $trabajadorSeleccionado = $_POST['trabajador_id'] ?? ''; // Mantener seleccionado

                while($row = $stmt->fetch()) {
                    $selected = ($trabajadorSeleccionado == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- FECHA -->
        <div class="col-md-6">
            <label class="form-label">Fecha</label>
            <input 
                type="date" 
                name="fecha" 
                class="form-control input-index " 
                required 
                value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">
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
                <b>{$trabajador['nombre']}</b> estará el día <b>$fecha</b> en el turno:<br>
                <h5 class='mt-2'>$turno</h5>
            </div>";
        } else {
            echo "<div class='alert alert-danger mt-4'>Trabajador no encontrado</div>";
        }
    }
    ?>
</div>

<hr class="my-3">
<div class="card shadow p-4">
<h4 class="mb-3 text-center ">Generar reporte mensual</h4>

<form action="reporte_excel.php" method="POST" class="row g-3">
  <div class="col-md-6">
   <label for="mes" class="form-label">Selecciona mes</label>
<input type="month" name="mes" id="mes" class="form-control input-index" required>
<small class="text-muted">Ejemplo: 2025-11</small>
  </div>

  <div class="col-md-6 d-flex align-items-end">
    <button type="submit" class="btn btn-success w-100">Descargar Excel</button>
  </div>
</form>
</div>
<hr class="my-3">
<?php include 'includes/footer.php'; ?>
