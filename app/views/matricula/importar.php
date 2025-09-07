<h2>📥 Importar Matrículas (CSV)</h2>

<?php if (!empty($errores)): ?>
  <div style="color:#b00; border:1px solid #b00; padding:8px; margin-bottom:10px;">
    <?= htmlspecialchars($errores) ?>
  </div>
<?php endif; ?>

<p>Formato recomendado (UTF-8, con cabecera):</p>
<pre style="background:#f7f7f7; padding:8px; overflow:auto;">
nie,alumnoNombre,idAlumno,anio,gradoNombre,idGrado,seccionNombre,idSeccion,modalidadNombre,idModalidad
1213321,Julian Juarez,,2025,1°,1,A,1,Desarrollo de Software,1
NIE0001,Ana López,,2025,1°,1,A,1,Desarrollo de Software,1
,NOMBRE EXACTO,,2025,,1,B,2,General,2
</pre>
<ul>
  <li>Debes incluir <strong>al menos una</strong> columna para identificar al alumno: <em>nie</em> o <em>idAlumno</em> o <em>alumnoNombre</em>.</li>
  <li>Para grado/sección/modalidad puedes usar <em>por nombre</em> (<code>gradoNombre</code>, <code>seccionNombre</code>, <code>modalidadNombre</code>) o <em>por ID</em> (<code>idGrado</code>, <code>idSeccion</code>, <code>idModalidad</code>).</li>
  <li><strong>anio</strong> es obligatorio.</li>
</ul>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=matricula&action=procesarImport" enctype="multipart/form-data">
  <label>Archivo CSV:</label>
  <input type="file" name="archivo" accept=".csv" required>
  <br><br>

  <label><input type="checkbox" name="simular" checked> Simular (no guarda, solo previsualiza)</label><br>
  <label><input type="checkbox" name="upsert" > Actualizar si ya existe matrícula del mismo alumno y año</label><br><br>

  <button type="submit">Procesar</button>
  &nbsp; <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Volver</a>
</form>
