<h2>Alumnos</h2>
<p><a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=crear">➕ Nuevo Alumno</a></p>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>ID</th><th>Nombre</th><th>NIE</th><th>Responsable</th><th>Teléfono</th><th>Modalidad</th><th>Sección</th><th>Año</th><th>Acciones</th>
  </tr>
  <?php foreach($items as $it): ?>
    <tr>
      <td><?= (int)$it['id_alumno'] ?></td>
      <td><?= htmlspecialchars($it['nombre']) ?></td>
      <td><?= htmlspecialchars($it['nie']) ?></td>
      <td><?= htmlspecialchars($it['responsable']) ?></td>
      <td><?= htmlspecialchars($it['num_responsable']) ?></td>
      <td><?= htmlspecialchars($it['modalidad']) ?></td>
      <td><?= htmlspecialchars($it['seccion']) ?></td>
      <td><?= htmlspecialchars($it['anio']) ?></td>
      <td>
        <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=editar&id=<?= (int)$it['id_alumno'] ?>">Editar</a> |
        <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=eliminar&id=<?= (int)$it['id_alumno'] ?>">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
