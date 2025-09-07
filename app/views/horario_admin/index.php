<h2>Gestor de Horario</h2>

<form method="GET" action="<?= BASE_URL ?>/index.php" style="margin-bottom:10px;">
  <input type="hidden" name="controller" value="horarioAdmin">
  <input type="hidden" name="action" value="index">

  <label>Grupo (Grado — Modalidad — Sección):</label>
  <select name="idGrupo">
    <?php foreach(($grupos ?? []) as $g): ?>
      <option value="<?= (int)$g['idGrupo'] ?>" <?= (isset($_GET['idGrupo']) && (int)$_GET['idGrupo']===(int)$g['idGrupo']) ? 'selected':'' ?>>
        <?= htmlspecialchars($g['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Ver</button>
  &nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=crear">➕ Nueva franja</a>
</form>

<?php if (empty($grupos)): ?>
  <div style="color:#b00;">No hay grupos definidos. Crea uno en: <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">Grupos</a></div>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Día</th><th>Inicio</th><th>Fin</th><th>Asignatura</th><th>Docente</th><th>Aula</th><th>Acciones</th>
  </tr>
  <?php if (!empty($items)): foreach($items as $h): ?>
    <tr>
      <td><?= htmlspecialchars($h['diaSemana']) ?></td>
      <td><?= htmlspecialchars(substr($h['horaInicio'],0,5)) ?></td>
      <td><?= htmlspecialchars(substr($h['horaFin'],0,5)) ?></td>
      <td><?= htmlspecialchars($h['nombreAsignatura']) ?></td>
      <td><?= htmlspecialchars($h['nombreUsuario']) ?></td>
      <td><?= htmlspecialchars($h['aula'] ?? '-') ?></td>
      <td>
        <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=editar&id=<?= (int)$h['idHorario'] ?>">Editar</a> |
        <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=eliminar&id=<?= (int)$h['idHorario'] ?>">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; else: ?>
    <tr><td colspan="7">Sin franjas para el grupo seleccionado.</td></tr>
  <?php endif; ?>
</table>
