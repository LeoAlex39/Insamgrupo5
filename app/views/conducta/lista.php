<h2>Incidentes de Hoy</h2>
<table border="1" cellpadding="6">
  <tr>
    <th>Alumno</th>
    <th>Tipo</th>
    <th>Severidad</th>
    <th>Detalle</th>
    <th>Fecha</th>
  </tr>
  <?php if (!empty($datos)): ?>
    <?php foreach ($datos as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['nombre']) ?> (<?= htmlspecialchars($d['nie']) ?>)</td>
        <td><?= htmlspecialchars($d['tipo']) ?></td>
        <td><?= htmlspecialchars($d['severidad']) ?></td>
        <td><?= htmlspecialchars($d['detalle']) ?></td>
        <td><?= htmlspecialchars($d['fecha']) ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="5">Sin incidentes registrados hoy para este horario.</td></tr>
  <?php endif; ?>
</table>
