<h2>Eliminar franja</h2>
<p>¿Eliminar la clase de <strong><?= htmlspecialchars($row['diaSemana']) ?></strong>
   de <strong><?= htmlspecialchars(substr($row['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($row['horaFin'],0,5)) ?></strong>?</p>
<form method="POST">
  <button type="submit">Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index">Cancelar</a>
</form>
