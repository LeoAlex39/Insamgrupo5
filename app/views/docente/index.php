<h2>Docentes</h2>
<p><a href="<?= BASE_URL ?>/index.php?controller=docente&action=crear">➕ Nuevo Docente</a></p>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Acciones</th></tr>
  <?php foreach($items as $it): ?>
    <tr>
      <td><?= (int)$it['idUsuario'] ?></td>
      <td><?= htmlspecialchars($it['nombreUsuario']) ?></td>
      <td><?= htmlspecialchars($it['correo']) ?></td>
      <td>
        <a href="<?= BASE_URL ?>/index.php?controller=docente&action=editar&id=<?= (int)$it['idUsuario'] ?>">Editar</a> |
        <a href="<?= BASE_URL ?>/index.php?controller=docente&action=eliminar&id=<?= (int)$it['idUsuario'] ?>">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
