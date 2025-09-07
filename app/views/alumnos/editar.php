<h2>Editar Alumno</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
  <label>Nombre:</label>
  <input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>

  <label>NIE:</label>
  <input type="text" name="nie" value="<?= htmlspecialchars($row['nie']) ?>" required>

  <label>Responsable:</label>
  <input type="text" name="responsable" value="<?= htmlspecialchars($row['responsable']) ?>" required>

  <label>Teléfono responsable:</label>
  <input type="text" name="num_responsable" value="<?= htmlspecialchars($row['num_responsable']) ?>" required>

  <label>Modalidad:</label>
  <select name="modalidad">
    <option <?= ($row['modalidad']==='Presencial'?'selected':'') ?>>Presencial</option>
    <option <?= ($row['modalidad']==='Virtual'?'selected':'') ?>>Virtual</option>
  </select>

  <label>Sección (letra):</label>
  <input type="text" name="seccion" maxlength="1" value="<?= htmlspecialchars($row['seccion']) ?>" required>

  <label>Año:</label>
  <input type="number" name="anio" value="<?= (int)$row['anio'] ?>" min="2020" max="2035" required>

  <br><br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index">Cancelar</a>
</form>
