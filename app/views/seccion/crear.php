<h2>Nueva Sección</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="POST">
  <label>Nombre:</label>
  <input type="text" name="nombreSeccion" required>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=seccion&action=index">Cancelar</a>
</form>
