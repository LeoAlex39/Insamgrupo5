<h2>📥 Resultado de importación de Matrículas</h2>

<p><strong>Filas leídas:</strong> <?= (int)$resultados['total'] ?></p>
<?php if (!$simular): ?>
  <p><strong>Insertados:</strong> <?= (int)$resultados['insertados'] ?>,
     <strong>Actualizados:</strong> <?= (int)$resultados['actualizados'] ?></p>
<?php else: ?>
  <p><em>Simulación activa (no se guardó nada).</em></p>
<?php endif; ?>

<?php if (!empty($resultados['errores'])): ?>
  <div style="color:#b00; border:1px solid #b00; padding:8px; margin:10px 0;">
    <strong>Errores:</strong>
    <ul>
      <?php foreach ($resultados['errores'] as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<h3>Vista previa de filas válidas</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>#</th>
    <th>idAlumno</th>
    <th>idGrado</th>
    <th>idSeccion</th>
    <th>idModalidad</th>
    <th>Año</th>
  </tr>
  <?php if (!empty($filasOK)): $i=1; foreach ($filasOK as $f): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= (int)$f['idAlumno'] ?></td>
      <td><?= (int)$f['idGrado'] ?></td>
      <td><?= (int)$f['idSeccion'] ?></td>
      <td><?= (int)$f['idModalidad'] ?></td>
      <td><?= (int)$f['anio'] ?></td>
    </tr>
  <?php endforeach; else: ?>
    <tr><td colspan="6">No hay filas válidas para mostrar.</td></tr>
  <?php endif; ?>
</table>

<br>
<a href="<?= BASE_URL ?>/index.php?controller=matricula&action=importar">⬅️ Volver al importador</a>
&nbsp;|&nbsp;
<a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">🏷️ Ir al listado</a>
