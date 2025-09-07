<h2>Eliminar Alumno</h2>
<p>¿Eliminar a <strong><?= htmlspecialchars($row['nombre']) ?></strong>?</p>
<form method="POST">
  <button type="submit">Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index">Cancelar</a>
</form>
