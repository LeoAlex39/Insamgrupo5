<h2>📥 Importador Híbrido (Alumnos + Matrículas)</h2>

<?php if (!empty($errores)): ?>
  <div style="color:#b00; border:1px solid #b00; padding:8px; margin-bottom:10px;">
    <?= htmlspecialchars($errores) ?>
  </div>
<?php endif; ?>

<p>Sube un CSV con columnas como:</p>
<pre style="background:#f7f7f7; padding:8px;">
alumnoNombre,nie,responsable,num_responsable,alumnoModalidad,seccion,anio,gradoNombre,seccionNombre,modalidadNombre
Pedro Castillo,NIE2001,Mamá,70112233,Presencial,A,2025,1°,A,Desarrollo de Software
</pre>

<p>También puedes usar IDs: <code>idGrado</code>, <code>idSeccion</code>, <code>idModalidad</code>.</p>

<div style="margin:8px 0;">
  Ejemplos:&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Volver</a>
</div>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=matricula&action=procesarImportHibrido" enctype="multipart/form-data">
  <label>Archivo CSV:</label>
  <input type="file" name="archivo" accept=".csv" required>
  <br><br>
  <label><input type="checkbox" name="simular" checked> Simular (previsualizar sin guardar)</label>
  <br><br>
  <button type="submit">Procesar</button>
</form>

<div style="margin-top:10px;">
  <strong>Descargas de ejemplo:</strong>
  <ul>
    <li><a href="sandbox:/mnt/data/matriculas_hibrido_completo.csv">matriculas_hibrido_completo.csv</a></li>
    <li><a href="sandbox:/mnt/data/matriculas_hibrido_minimo.csv">matriculas_hibrido_minimo.csv</a></li>
  </ul>
</div>
