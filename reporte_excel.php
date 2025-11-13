<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ---------------------- Función de Turno ----------------------
function turnoTrabajador($fechaConsulta, $fechaInicio) {
    $turnos = [
        0 => [
            'modalidad' => 'Turno Tarde (Lun-Sáb 3pm-11pm)',
            'horario_lv' => ['15:00 p.m','23:00 p.m'],
            'horario_sab'=> ['15:00 p.m','23:00 p.m'],
            'refrigerio' => ['',''],
            'horas_lv' => 40,
            'horas_sab'=> 8
        ],
        1 => [
            'modalidad' => 'Turno Mañana (Lun-Sáb 7am-4pm)',
            'horario_lv' => ['07:00 a.m','16:00 p.m'],
            'horario_sab'=> ['07:00 a.m','15:00 p.m'],
            'refrigerio' => ['12:00','13:00'],
            'horas_lv' => 40,
            'horas_sab'=> 8
        ],
        2 => [
            'modalidad' => 'Turno Mañana (Lun-Vie 6:45am-5pm)',
            'horario_lv' => ['06:45 a.m','17:00 p.m'],
            'horario_sab'=> ['06:45 a.m','15:00 p.m'],
            'refrigerio' => ['13:00','14:00'],
            'horas_lv' => 46.5,
            'horas_sab'=> 8
        ],
    ];

    $inicio = new DateTime($fechaInicio);
    $consulta = new DateTime($fechaConsulta);

    $diff = $inicio->diff($consulta);
    $dias = (int)$diff->days;
    $semanas = floor($dias / 7);
    $idx = $semanas % 3;

    return $turnos[$idx];
}

// ---------------------- Obtener mes ----------------------
$mesSeleccionado = $_POST['mes'] ?? $_GET['mes'] ?? '';
if (empty($mesSeleccionado)) {
    die("Mes no especificado.");
}
list($anio, $mes) = explode('-', $mesSeleccionado);

// ---------------------- Datos ----------------------
setlocale(LC_TIME, 'es_ES.UTF-8');
$nombreMes = strftime('%B', strtotime("$anio-$mes-01"));
$nombreMes = mb_strtoupper($nombreMes, 'UTF-8');

$stmt = $pdo->query("SELECT id, nombre, fecha_inicio FROM trabajadores ORDER BY id");
$trabajadores = $stmt->fetchAll();

// ---------------------- Crear hoja ----------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Turnos $mes-$anio");

// Estilos
$titleFill = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '9AC6E0']]; // azul claro
$headerFill = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1F4366']]; // azul oscuro
$headerFontColor = ['argb' => 'FFFFFF'];
$center = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];

// ---------------------- Fechas ----------------------
$fila = 1;
$semana = 1;

$primerDia = new DateTime("$anio-$mes-01");
$ultimoDiaNum = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
$ultimoDia = new DateTime("$anio-$mes-$ultimoDiaNum");

$weekStart = clone $primerDia;
if ($weekStart->format('N') != 1) {
    $weekStart->modify('next monday');
}

// ---------------------- Generar reporte por semanas ----------------------
while ($weekStart <= $ultimoDia) {
    $lunes = clone $weekStart;
    $viernes = (clone $lunes)->modify('+4 days');
    $sabado = (clone $lunes)->modify('+5 days');

    if ($viernes > $ultimoDia) $viernes = clone $ultimoDia;
    if ($sabado > $ultimoDia) $sabado = clone $ultimoDia;

    // ======= LUNES A VIERNES =======
    $sheet->mergeCells("A{$fila}:F{$fila}");
    $sheet->setCellValue("A{$fila}", "LUNES A VIERNES SEMANA {$semana} - {$nombreMes} {$anio}");
    $sheet->getStyle("A{$fila}")->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle("A{$fila}")->getFill()->applyFromArray($titleFill);
    $sheet->getStyle("A{$fila}")->getAlignment()->applyFromArray($center);
    $fila++;

    $sheet->mergeCells("A{$fila}:F{$fila}");
    $sheet->setCellValue("A{$fila}", $lunes->format('d/m') . " al " . $viernes->format('d/m') . " del {$anio}");
    $sheet->getStyle("A{$fila}")->getAlignment()->applyFromArray($center);
    $fila++;

    $sheet->fromArray(['Analista','Modalidad','Horas trabajadas','Horario Lunes - Viernes','Refrigerio (Inicio)','Refrigerio (Fin)'], null, "A{$fila}");
    $sheet->getStyle("A{$fila}:F{$fila}")->getFont()->setBold(true);
    $sheet->getStyle("A{$fila}:F{$fila}")->getFill()->applyFromArray($headerFill);
    $sheet->getStyle("A{$fila}:F{$fila}")->getFont()->getColor()->applyFromArray($headerFontColor);
    $sheet->getStyle("A{$fila}:F{$fila}")->getAlignment()->applyFromArray($center);
    $sheet->getStyle("A{$fila}:F{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $fila++;

    foreach ($trabajadores as $t) {
        $turnoInfo = turnoTrabajador($lunes->format('Y-m-d'), $t['fecha_inicio']);

        $sheet->fromArray([
            $t['nombre'],
            $turnoInfo['modalidad'],
            $turnoInfo['horas_lv'],
            $turnoInfo['horario_lv'][0] . ' - ' . $turnoInfo['horario_lv'][1],
            $turnoInfo['refrigerio'][0],
            $turnoInfo['refrigerio'][1]
        ], null, "A{$fila}");

        $sheet->getStyle("A{$fila}:F{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $fila++;
    }

    $fila++; // Espacio

    // ======= SÁBADO =======
    $sheet->mergeCells("A{$fila}:F{$fila}");
    $sheet->setCellValue("A{$fila}", "SÁBADO SEMANA {$semana} - {$nombreMes} {$anio}");
    $sheet->getStyle("A{$fila}")->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle("A{$fila}")->getFill()->applyFromArray($titleFill);
    $sheet->getStyle("A{$fila}")->getAlignment()->applyFromArray($center);
    $fila++;

    $sheet->mergeCells("A{$fila}:F{$fila}");
    $sheet->setCellValue("A{$fila}", $sabado->format('d/m') . " del {$anio}");
    $sheet->getStyle("A{$fila}")->getAlignment()->applyFromArray($center);
    $fila++;

    $sheet->fromArray(['Analista','Modalidad','Horas trabajadas','Horario Sábado','Refrigerio (Inicio)','Refrigerio (Fin)'], null, "A{$fila}");
    $sheet->getStyle("A{$fila}:F{$fila}")->getFont()->setBold(true);
    $sheet->getStyle("A{$fila}:F{$fila}")->getFill()->applyFromArray($headerFill);
    $sheet->getStyle("A{$fila}:F{$fila}")->getFont()->getColor()->applyFromArray($headerFontColor);
    $sheet->getStyle("A{$fila}:F{$fila}")->getAlignment()->applyFromArray($center);
    $sheet->getStyle("A{$fila}:F{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $fila++;

    foreach ($trabajadores as $t) {
        $turnoInfo = turnoTrabajador($sabado->format('Y-m-d'), $t['fecha_inicio']);

        // Omitir los turnos LUN-VIE
        if (stripos($turnoInfo['modalidad'], 'Lun-Vie') !== false) {
            continue;
        }

        $sheet->fromArray([
            $t['nombre'],
            $turnoInfo['modalidad'],
            $turnoInfo['horas_sab'],
            $turnoInfo['horario_sab'][0] . ' - ' . $turnoInfo['horario_sab'][1],
            '', // sin refrigerio sábado
            ''
        ], null, "A{$fila}");

        $sheet->getStyle("A{$fila}:F{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $fila++;
    }

    $fila += 2;
    $semana++;
    $weekStart->modify('+7 days'); // siguiente lunes
}

// Ajustar anchos
foreach (range('A','F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("C1:F{$fila}")->getAlignment()->applyFromArray($center);

// ---------------------- Descargar ----------------------
$filename = "reporte_turnos_{$mesSeleccionado}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
