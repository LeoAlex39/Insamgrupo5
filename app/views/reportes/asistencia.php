<h2>📊 Reporte de Asistencia</h2>
<p>Período: <?= htmlspecialchars($_POST['fechaInicio']) ?> a <?= htmlspecialchars($_POST['fechaFin']) ?></p>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Alumno</th>
    <th>NIE</th>
    <th>Presentes</th>
    <th>Ausentes</th>
    <th>Tardes</th>
    <th>Excusas</th>
    <th>Total</th>
    <th>% Asistencia</th>
  </tr>
  <?php if (!empty($datos)): ?>
    <?php foreach ($datos as $d): 
      $total = max(1,(int)$d['total']); 
      $pct = round(($d['presentes'] / $total) * 100,1);
    ?>
      <tr>
        <td><?= htmlspecialchars($d['nombre']) ?></td>
        <td><?= htmlspecialchars($d['nie']) ?></td>
        <td><?= $d['presentes'] ?></td>
        <td><?= $d['ausentes'] ?></td>
        <td><?= $d['tardes'] ?></td>
        <td><?= $d['excusas'] ?></td>
        <td><?= $d['total'] ?></td>
        <td><?= $pct ?>%</td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="8">No hay registros de asistencia en este período.</td></tr>
  <?php endif; ?>
</table>

<br>
<a href="<?= BASE_URL ?>/index.php?controller=reporte&action=index">⬅️ Nuevo reporte</a>
