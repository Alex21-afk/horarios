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
<!-- Banner de bienvenida -->
<div class="card shadow-lg p-4 mb-4" style="background: white; position: relative;">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-2"><i class="bi bi-person-workspace me-2"></i>Panel de Administración</h4>
            <p class="text-muted mb-0">Gestiona los turnos y trabajadores del sistema</p>
        </div>
        <div class="col-md-4 text-end mt-3 mt-md-0">
            <a href="includes/trabajadores/lista_tra.php" class="btn btn-index">
                <i class="bi bi-people-fill me-2"></i>Gestionar Trabajadores
            </a>
        </div>
    </div>
</div>
<!-- Fin: banner -->
 
<div class="card shadow-lg p-4 mb-4">
    <div class="text-center mb-4">
        <i class="bi bi-clock-history" style="font-size: 3rem; color: var(--accent);"></i>
        <h3 class="mt-3">Consulta de Turno</h3>
        <p class="text-muted">Selecciona un trabajador y fecha para consultar su turno asignado</p>
    </div>

    <form method="POST" class="row g-4">

        <!-- SELECT TRABAJADOR -->
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-fill me-2"></i>Trabajador</label>
            <select class="form-select" name="trabajador_id" required>
                <option value="">🔍 Seleccionar trabajador...</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM trabajadores ORDER BY nombre ASC");
                $trabajadorSeleccionado = $_POST['trabajador_id'] ?? '';

                while($row = $stmt->fetch()) {
                    $selected = ($trabajadorSeleccionado == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- FECHA -->
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-calendar-event me-2"></i>Fecha de Consulta</label>
            <input 
                type="date" 
                name="fecha" 
                class="form-control" 
                required 
                value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : date('Y-m-d') ?>">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-index w-100">
                <i class="bi bi-search me-2"></i>Consultar Turno
            </button>
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
            $fechaFormateada = date('d/m/Y', strtotime($fecha));

            echo "
            <div class='alert alert-info mt-4 border-0' style='animation: fadeInUp 0.5s ease;'>
                <div class='d-flex align-items-center mb-3'>
                    <i class='bi bi-check-circle-fill me-3' style='font-size: 2.5rem; color: var(--accent);'></i>
                    <div>
                        <h5 class='mb-1' style='color: var(--primary);'>Resultado de la Consulta</h5>
                        <small class='text-muted'>Información actualizada</small>
                    </div>
                </div>
                <div class='ps-2'>
                    <p class='mb-2'><i class='bi bi-person-badge me-2'></i><strong>Trabajador:</strong> {$trabajador['nombre']}</p>
                    <p class='mb-2'><i class='bi bi-calendar-check me-2'></i><strong>Fecha:</strong> $fechaFormateada</p>
                    <div class='p-3 mt-3 rounded' style='background: rgba(0, 180, 216, 0.1); border-left: 4px solid var(--accent);'>
                        <p class='mb-1'><strong>Turno Asignado:</strong></p>
                        <h5 class='mb-0' style='color: var(--accent);'><i class='bi bi-clock me-2'></i>$turno</h5>
                    </div>
                </div>
            </div>";
        } else {
            echo "<div class='alert alert-danger mt-4 border-0'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i>Trabajador no encontrado
                  </div>";
        }
    }
    ?>
</div>

<hr class="my-4">

<div class="card shadow-lg p-4">
    <div class="text-center mb-4">
        <i class="bi bi-file-earmark-excel" style="font-size: 3rem; color: var(--success);"></i>
        <h4 class="mt-3">Generar Reporte Mensual</h4>
        <p class="text-muted">Exporta los turnos del mes seleccionado en formato Excel</p>
    </div>

    <form action="reporte_excel.php" method="POST" class="row g-4">
        <div class="col-md-8">
            <label for="mes" class="form-label"><i class="bi bi-calendar-range me-2"></i>Selecciona el Mes</label>
            <input type="month" name="mes" id="mes" class="form-control" required 
                   value="<?= date('Y-m') ?>" 
                   max="<?= date('Y-m', strtotime('+1 year')) ?>">
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Formato: Año-Mes (Ejemplo: 2025-11)</small>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-download me-2"></i>Descargar Excel
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
