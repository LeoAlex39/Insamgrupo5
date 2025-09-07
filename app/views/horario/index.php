<h2>📅 Mi horario</h2>

<!-- Filtros por día -->
<form method="GET" action="index.php">
  <input type="hidden" name="controller" value="horario">
  <input type="hidden" name="action" value="index">
  <label>Filtrar por día:</label>
  <select name="dia" onchange="this.form.submit()">
    <option value="">Todos</option>
    <?php 
      $diasNombre = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
      foreach ($diasNombre as $d):
        $sel = (isset($_GET['dia']) && $_GET['dia'] === $d) ? 'selected' : '';
        $disponible = empty($diasDisponibles) ? true : in_array($d, $diasDisponibles);
    ?>
      <option value="<?= $d ?>" <?= $sel ?> <?= $disponible ? '' : 'disabled' ?>><?= $d ?></option>
    <?php endforeach; ?>
  </select>
  <noscript><button type="submit">Aplicar</button></noscript>
</form>

<br>

<table border="1" cellpadding="6" cellspacing="0">
  <tr>
    <th>Día</th>
    <th>Hora</th>
    <th>Asignatura</th>
    <th>Grado / Sección</th>
    <th>Modalidad</th>
    <th>Aula</th>
    <th>Acciones</th>
  </tr>
  <?php if (!empty($items)): ?>
    <?php foreach ($items as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['diaSemana']) ?></td>
        <td><?= htmlspecialchars(substr($row['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($row['horaFin'],0,5)) ?></td>
        <td><?= htmlspecialchars($row['nombreAsignatura']) ?></td>
        <td><?= htmlspecialchars($row['nombreGrado']) ?> / <?= htmlspecialchars($row['nombreSeccion']) ?></td>
        <td><?= htmlspecialchars($row['nombreModalidad']) ?></td>
        <td><?= htmlspecialchars($row['aula'] ?? '-') ?></td>
        <td>
          <!-- Accesos directos a módulos vinculados a este idHorario -->
<a href="<?= BASE_URL ?>/index.php?controller=asistencia&action=registrar&idHorario=<?= (int)$row['idHorario'] ?>">📝 Pasar asistencia</a>

        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="7">No hay clases para el criterio seleccionado.</td></tr>
  <?php endif; ?>
</table>
