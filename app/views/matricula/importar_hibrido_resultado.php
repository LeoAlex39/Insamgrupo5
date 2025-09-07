<h2>📥 Resultado Importador Híbrido</h2>

<p><strong>Filas leídas:</strong> <?= (int)$resultados['total'] ?></p>
<p><strong>Alumnos creados:</strong> <?= (int)$resultados['creados_alumno'] ?></p>
<?php if (!isset($simular) || !$simular): ?>
  <p><strong>Insertados matrícula:</strong> <?= (int)$resultados['insertados'] ?>,
     <strong>Actualizados matrícula:</strong> <?= (int)$resultados['actualizados'] ?></p>
<?php else: ?>
  <p><em>Simulación activa – no se guardaron cambios.</em></p>
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

<h3>Vista previa de operaciones de matrícula</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>#</th>
    <th>idAlumno</th>
    <th>idGrado</th>
    <th>idSeccion</th>
    <th>idModalidad</th>
    <th>Año</th>
  </tr>
  <?php if (!empty($preview)): $i=1; foreach ($preview as $p): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= (int)$p['idAlumno'] ?></td>
      <td><?= (int)$p['idGrado'] ?></td>
      <td><?= (int)$p['idSeccion'] ?></td>
      <td><?= (int)$p['idModalidad'] ?></td>
      <td><?= (int)$p['anio'] ?></td>
    </tr>
  <?php endforeach; else: ?>
    <tr><td colspan="6">No hay filas válidas para mostrar.</td></tr>
  <?php endif; ?>
</table>

<br>
<a href="<?= BASE_URL ?>/index.php?controller=matricula&action=importarHibrido">⬅️ Volver al importador híbrido</a>
&nbsp;|&nbsp;
<a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">🏷️ Ir al listado</a>
