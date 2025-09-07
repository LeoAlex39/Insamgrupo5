<h2>Grupos (Grado + Modalidad + Sección)</h2>
<p><a href="<?= BASE_URL ?>/index.php?controller=grupo&action=crear">➕ Nuevo grupo</a></p>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr>
    <th>ID</th><th>Nombre</th><th>Grado</th><th>Modalidad</th><th>Sección</th><th>Alias</th><th>Acciones</th>
  </tr>
  <?php foreach($items as $it): ?>
    <tr>
      <td><?= (int)$it['idGrupo'] ?></td>
      <td><?= htmlspecialchars($it['nombre']) ?></td>
      <td><?= htmlspecialchars($it['nombreGrado']) ?></td>
      <td><?= htmlspecialchars($it['nombreModalidad']) ?></td>
      <td><?= htmlspecialchars($it['nombreSeccion']) ?></td>
      <td><?= htmlspecialchars($it['alias'] ?? '') ?></td>
      <td>
        <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=editar&id=<?= (int)$it['idGrupo'] ?>">Editar</a> |
        <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=eliminar&id=<?= (int)$it['idGrupo'] ?>">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
