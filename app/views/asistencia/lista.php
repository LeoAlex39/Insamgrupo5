<h2>Asistencia registrada hoy</h2>

<?php if (!empty($infoClase)): ?>
<div style="padding:10px;border:1px solid #ccc;margin-bottom:10px;">
  <strong>Clase:</strong> <?= htmlspecialchars($infoClase['nombreAsignatura']) ?>
  &nbsp;|&nbsp; <strong>Grado/Sección:</strong> <?= htmlspecialchars($infoClase['nombreGrado']) ?> - <?= htmlspecialchars($infoClase['nombreSeccion']) ?>
  &nbsp;|&nbsp; <strong>Día:</strong> <?= htmlspecialchars($infoClase['diaSemana']) ?>
</div>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Alumno</th>
    <th>NIE</th>
    <th>Estado</th>
    <th>Observación</th>
    <th>Fecha</th>
  </tr>
  <?php if (!empty($datos)): ?>
    <?php foreach ($datos as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['nombre']) ?></td>
        <td><?= htmlspecialchars($d['nie']) ?></td>
        <td><?= htmlspecialchars($d['estado']) ?></td>
        <td><?= htmlspecialchars($d['observacion']) ?></td>
        <td><?= htmlspecialchars($d['fecha']) ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="5">Aún no hay registros de hoy.</td></tr>
  <?php endif; ?>
</table>

<br>
<a href="<?= BASE_URL ?>/index.php?controller=horario&action=index">⬅️ Volver a mi horario</a>
