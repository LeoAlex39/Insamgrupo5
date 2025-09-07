<h2>Eliminar Docente</h2>
<p>¿Eliminar al docente <strong><?= htmlspecialchars($row['nombreUsuario']) ?></strong>?</p>
<form method="POST">
  <button type="submit">Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=docente&action=index">Cancelar</a>
</form>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
