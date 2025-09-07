<h2>Editar franja</h2>

<?php if (!empty($errorList)): ?>
  <div style="color:#b00;">
    <?php foreach($errorList as $e): ?>
      <div>• <?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=editar&id=<?= (int)$row['idHorario'] ?>">
  <label>Día:</label>
  <select name="diaSemana">
    <?php foreach (['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $d): ?>
      <option value="<?= $d ?>" <?= ($d === $row['diaSemana']) ? 'selected' : '' ?>><?= $d ?></option>
    <?php endforeach; ?>
  </select>

  <label>Inicio:</label>
  <input type="time" name="horaInicio" value="<?= htmlspecialchars(substr($row['horaInicio'], 0, 5)) ?>" required>

  <label>Fin:</label>
  <input type="time" name="horaFin" value="<?= htmlspecialchars(substr($row['horaFin'], 0, 5)) ?>" required>

  <label>Aula:</label>
  <input type="text" name="aula" value="<?= htmlspecialchars($row['aula'] ?? '') ?>" placeholder="Opcional">

  <label>Docente + Asignatura:</label>
  <select name="idDocenteAsignatura" required>
    <?php foreach ($docAsig as $da): ?>
      <option value="<?= (int)$da['idDocenteAsignatura'] ?>"
        <?= ((int)$da['idDocenteAsignatura'] === (int)$row['idDocenteAsignatura']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($da['nombreUsuario'] . ' — ' . $da['nombreAsignatura']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <br><br>
  <button type="submit">Guardar cambios</button>
  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index">Cancelar</a>
</form>
