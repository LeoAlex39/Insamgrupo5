<h2>Nuevo Alumno</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
  <label>Nombre:</label>
  <input type="text" name="nombre" required>

  <label>NIE:</label>
  <input type="text" name="nie" required>

  <label>Responsable:</label>
  <input type="text" name="responsable" required>

  <label>Teléfono responsable:</label>
  <input type="text" name="num_responsable" required>

  <label>Modalidad:</label>
  <select name="modalidad">
    <option>Presencial</option>
    <option>Virtual</option>
  </select>

  <label>Sección (letra):</label>
  <input type="text" name="seccion" maxlength="1" value="A" required>

  <label>Año:</label>
  <input type="number" name="anio" value="<?= date('Y') ?>" min="2020" max="2035" required>

  <br><br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index">Cancelar</a>
</form>
