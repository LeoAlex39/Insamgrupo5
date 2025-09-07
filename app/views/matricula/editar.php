<h2>✏️ Editar matrícula</h2>

<?php if (!empty($error)): ?>
  <div style="color:#b00; border:1px solid #b00; padding:8px; margin-bottom:10px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=matricula&action=update">
  <input type="hidden" name="idMatricula" value="<?= (int)$row['idMatricula'] ?>">

  <label>Alumno:</label>
  <select name="idAlumno" required>
    <?php foreach ($alumnos as $a): ?>
      <option value="<?= $a['id_alumno'] ?>" <?= ($a['id_alumno']==$row['idAlumno'])?'selected':'' ?>>
        <?= htmlspecialchars($a['nombre']) ?> (<?= htmlspecialchars($a['nie']) ?>)
      </option>
    <?php endforeach; ?>
  </select>
  <br>

  <label>Grado:</label>
  <select name="idGrado" required>
    <?php foreach ($grados as $g): ?>
      <option value="<?= $g['idGrado'] ?>" <?= ($g['idGrado']==$row['idGrado'])?'selected':'' ?>>
        <?= htmlspecialchars($g['nombreGrado']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br>

  <label>Sección:</label>
  <select name="idSeccion" required>
    <?php foreach ($secciones as $s): ?>
      <option value="<?= $s['idSeccion'] ?>" <?= ($s['idSeccion']==$row['idSeccion'])?'selected':'' ?>>
        <?= htmlspecialchars($s['nombreSeccion']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br>

  <label>Modalidad:</label>
  <select name="idModalidad" required>
    <?php foreach ($modalidades as $m): ?>
      <option value="<?= $m['idModalidad'] ?>" <?= ($m['idModalidad']==$row['idModalidad'])?'selected':'' ?>>
        <?= htmlspecialchars($m['nombreModalidad']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br>

  <label>Año lectivo:</label>
  <input type="number" name="anio" value="<?= (int)$row['anio'] ?>" min="2020" max="2035" required>
  <br><br>

  <button type="submit">Guardar cambios</button>
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Cancelar</a>
</form>
