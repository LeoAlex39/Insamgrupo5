<h2>📚 Matrícula — Listado</h2>

<form method="GET" action="<?= BASE_URL ?>/index.php">
  <input type="hidden" name="controller" value="matricula">
  <input type="hidden" name="action" value="index">

  <label>Año:</label>
  <input type="number" name="anio" value="<?= htmlspecialchars($_GET['anio'] ?? '') ?>" min="2020" max="2035">

  <label>Grado:</label>
  <select name="idGrado">
    <option value="">Todos</option>
    <?php foreach ($grados as $g): ?>
      <option value="<?= $g['idGrado'] ?>" <?= (($_GET['idGrado'] ?? '')==$g['idGrado'])?'selected':'' ?>>
        <?= htmlspecialchars($g['nombreGrado']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Sección:</label>
  <select name="idSeccion">
    <option value="">Todas</option>
    <?php foreach ($secciones as $s): ?>
      <option value="<?= $s['idSeccion'] ?>" <?= (($_GET['idSeccion'] ?? '')==$s['idSeccion'])?'selected':'' ?>>
        <?= htmlspecialchars($s['nombreSeccion']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Modalidad:</label>
  <select name="idModalidad">
    <option value="">Todas</option>
    <?php foreach ($modalidades as $m): ?>
      <option value="<?= $m['idModalidad'] ?>" <?= (($_GET['idModalidad'] ?? '')==$m['idModalidad'])?'selected':'' ?>>
        <?= htmlspecialchars($m['nombreModalidad']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Filtrar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Limpiar</a>
  &nbsp;&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=crear">➕ Nueva matrícula</a>
    &nbsp;&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=importar">⬆️ Importar CSV</a>
    &nbsp;&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=importarHibrido">⬆️ Importador Híbrido</a>


</form>

<br>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>Año</th>
    <th>Alumno</th>
    <th>NIE</th>
    <th>Grado</th>
    <th>Sección</th>
    <th>Modalidad</th>
    <th>Acciones</th>
  </tr>
  <?php if (!empty($rows)): ?>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['anio']) ?></td>
        <td><?= htmlspecialchars($r['alumno']) ?></td>
        <td><?= htmlspecialchars($r['nie']) ?></td>
        <td><?= htmlspecialchars($r['nombreGrado']) ?></td>
        <td><?= htmlspecialchars($r['nombreSeccion']) ?></td>
        <td><?= htmlspecialchars($r['nombreModalidad']) ?></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=editar&id=<?= (int)$r['idMatricula'] ?>">✏️ Editar</a>
          |
          <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=eliminar&id=<?= (int)$r['idMatricula'] ?>">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="7">Sin resultados con los filtros seleccionados.</td></tr>
  <?php endif; ?>
</table>
