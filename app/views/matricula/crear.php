<h2>➕ Nueva matrícula</h2>

<?php if (!empty($error)): ?>
  <div style="color:#b00; border:1px solid #b00; padding:8px; margin-bottom:10px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=matricula&action=store">
  <label>Alumno:</label>
  <select name="idAlumno" required>
    <option value="">Seleccione...</option>
    <?php foreach ($alumnos as $a): ?>
      <option value="<?= $a['id_alumno'] ?>"><?= htmlspecialchars($a['nombre']) ?> (<?= htmlspecialchars($a['nie']) ?>)</option>
    <?php endforeach; ?>
  </select>
  <br>

  <!-- NUEVO: selector único de Grupo (Grado+Modalidad+Sección) -->
  <label>Grupo (Grado — Modalidad — Sección):</label>
  <select name="idGrupo" required>
    <option value="">Seleccione...</option>
    <?php foreach ($grupos as $g): ?>
      <option value="<?= (int)$g['idGrupo'] ?>">
        <?= htmlspecialchars($g['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br>

  <label>Año lectivo:</label>
  <input type="number" name="anio" value="<?= date('Y') ?>" min="2020" max="2035" required>
  <br><br>

  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Cancelar</a>
</form>
