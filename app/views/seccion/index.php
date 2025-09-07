<h2>Secciones</h2>
<p><a href="<?= BASE_URL ?>/index.php?controller=seccion&action=crear">➕ Nueva Sección</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
  <?php foreach($items as $it): ?>
    <tr>
      <td><?= (int)$it['idSeccion'] ?></td>
      <td><?= htmlspecialchars($it['nombreSeccion']) ?></td>
      <td>
        <a href="<?= BASE_URL ?>/index.php?controller=seccion&action=editar&id=<?= (int)$it['idSeccion'] ?>">Editar</a> |
        <a href="<?= BASE_URL ?>/index.php?controller=seccion&action=eliminar&id=<?= (int)$it['idSeccion'] ?>">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
