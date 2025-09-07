<?php
// Usa variables del POST y arrays $resumen (por alumno) y $tipos (distribución por tipo)
$fechaIni = htmlspecialchars($_POST['fechaInicio']);
$fechaFin = htmlspecialchars($_POST['fechaFin']);
$idGrado = (int)$_POST['idGrado'];
$idSeccion = (int)$_POST['idSeccion'];
$idModalidad = (int)$_POST['idModalidad'];
$anio = (int)$_POST['anio'];
?>
<h2>📊 Reporte de Conducta</h2>
<p>Período: <?= $fechaIni ?> a <?= $fechaFin ?></p>

<div style="margin:10px 0;">
  <a
    href="<?= BASE_URL ?>/index.php?controller=reporteConducta&action=csv&idGrado=<?= $idGrado ?>&idSeccion=<?= $idSeccion ?>&idModalidad=<?= $idModalidad ?>&anio=<?= $anio ?>&fechaInicio=<?= $fechaIni ?>&fechaFin=<?= $fechaFin ?>"
  >⬇️ Exportar CSV</a>
  &nbsp;|&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=reporteConducta&action=index">⬅️ Nuevo reporte</a>
</div>

<h3>Resumen por alumno (severidad)</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Alumno</th>
    <th>NIE</th>
    <th>Baja</th>
    <th>Media</th>
    <th>Alta</th>
    <th>Total</th>
  </tr>
  <?php if (!empty($resumen)): ?>
    <?php foreach ($resumen as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['nombre']) ?></td>
        <td><?= htmlspecialchars($r['nie']) ?></td>
        <td><?= (int)$r['bajas'] ?></td>
        <td><?= (int)$r['medias'] ?></td>
        <td><?= (int)$r['altas'] ?></td>
        <td><?= (int)$r['total'] ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="6">No hay incidentes en este período.</td></tr>
  <?php endif; ?>
</table>

<br>

<h3>Distribución por tipo de incidente</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Tipo</th>
    <th>Cantidad</th>
  </tr>
  <?php if (!empty($tipos)): ?>
    <?php foreach ($tipos as $t): ?>
      <tr>
        <td><?= htmlspecialchars($t['tipo']) ?></td>
        <td><?= (int)$t['cantidad'] ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="2">Sin registros por tipo en este período.</td></tr>
  <?php endif; ?>
</table>
