<h2>Nuevo Docente</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
  <label>Nombre:</label>
  <input type="text" name="nombreUsuario" required>

  <label>Correo:</label>
  <input type="email" name="correo" required>

  <label>Contraseña:</label>
  <input type="password" name="password" required>

  <label>Repetir contraseña:</label>
  <input type="password" name="password2" required>

  <fieldset style="margin-top:12px;">
    <legend>Asignaturas</legend>
    <?php foreach ($asignaturas as $a): ?>
      <label style="display:block;">
        <input type="checkbox" name="asignaturas[]" value="<?= (int)$a['idAsignatura'] ?>">
        <?= htmlspecialchars($a['nombreAsignatura']) ?>
      </label>
    <?php endforeach; ?>
  </fieldset>

  <br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=docente&action=index">Cancelar</a>
</form>
