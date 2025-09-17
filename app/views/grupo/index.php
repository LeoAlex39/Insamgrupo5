<div class="content">
  <h2>Grupos</h2>
  <p>
    <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=crear" class="btn btn-primary">
      ➕ Nuevo grupo
    </a>
  </p>

  <style>
    /* Contenedor general */
.content {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
  margin: 20px auto;
  max-width: 1000px;
}

/* Título */
.content h2 {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

/* Botón principal */
.btn.btn-primary {
  background: #0097a7;
  color: #fff;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.3s ease;
  display: inline-block;
}

.btn.btn-primary:hover {
  background: #007c8a;
}

/* Botones pequeños */
.btn-sm {
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
  text-decoration: none;
  font-weight: 500;
}

.btn-warning {
  background: #ffc107;
  color: #000;
}

.btn-warning:hover {
  background: #e0a800;
}

.btn-danger {
  background: #dc3545;
  color: #fff;
}

.btn-danger:hover {
  background: #c82333;
}

/* Tabla */
.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-top: 15px;
  font-size: 15px;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0px 2px 6px rgba(0,0,0,0.05);
}

.table thead {
  background: #007c8a;
  color: white;
}

.table th, .table td {
  padding: 12px 15px;
  text-align: left;
}

.table th {
  font-weight: 600;
}

.table tbody tr {
  transition: background 0.2s ease;
}

.table tbody tr:hover {
  background: #f9fafb;
}

/* Acciones centradas */
.table td:last-child {
  display: flex;
  gap: 8px;
}

  </style>

  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Grado</th>
        <th>Modalidad</th>
        <th>Sección</th>
        <th>Alias</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?= (int)$it['idGrupo'] ?></td>
          <td><?= htmlspecialchars($it['nombre']) ?></td>
          <td><?= htmlspecialchars($it['nombreGrado']) ?></td>
          <td><?= htmlspecialchars($it['nombreModalidad']) ?></td>
          <td><?= htmlspecialchars($it['nombreSeccion']) ?></td>
          <td><?= htmlspecialchars($it['alias'] ?? '') ?></td>
          <td>
            <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=editar&id=<?= (int)$it['idGrupo'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
            <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=eliminar&id=<?= (int)$it['idGrupo'] ?>" class="btn btn-sm btn-danger">🗑️ Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
