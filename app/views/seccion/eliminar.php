<h2>Eliminar Sección</h2>
<p>¿Eliminar la sección <strong><?= htmlspecialchars($row['nombreSeccion']) ?></strong>?</p>
<form method="POST">
  <button type="submit">Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=seccion&action=index">Cancelar</a>
</form>
