<h2>Gestor de Horario</h2>

<style> 
  /* Contenedor principal */
h2 {
  font-size: 22px;
  font-weight: 600;
  color: #333;
  margin-bottom: 20px;
}

/* Formulario */
form {
  background: #fff;
  padding: 16px;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 20px;
}

form label {
  font-weight: 500;
  color: #444;
}

form select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  transition: border 0.2s ease;
}

form select:focus {
  border-color: #0097a7;
  box-shadow: 0 0 0 2px rgba(0,151,167,0.2);
}

/* Botón principal */
form button {
  background: #0097a7;
  color: #fff;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.3s ease;
}

form button:hover {
  background: #007c8a;
}

/* Botón secundario (nuevo enlace) */
form a {
  background: #0097a7;
  color: #fff;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.3s ease;
}

form a:hover {
  background: #007c8a;
}

/* Mensaje de advertencia */
div[style*="color:#b00;"] {
  background: #ffe5e5;
  padding: 12px;
  border-radius: 8px;
  color: #b00;
  margin-top: 10px;
  font-size: 14px;
}

/* Tabla */
table {
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

table th {
  background: #007c8a;
  color: white;
  font-weight: 600;
  padding: 12px 15px;
  text-align: left;
}

table td {
  padding: 12px 15px;
  border-top: 1px solid #f0f0f0;
  color: #444;
}

/* Hover en filas */
table tbody tr:hover {
  background: #f9fafb;
}

/* Acciones */
table td:last-child a {
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
  margin-right: 6px;
  transition: background 0.3s ease;
}

table td:last-child a[href*="editar"] {
  background: #ffc107;
  color: #000;
}

table td:last-child a[href*="editar"]:hover {
  background: #e0a800;
}

table td:last-child a[href*="eliminar"] {
  background: #dc3545;
  color: #fff;
}

table td:last-child a[href*="eliminar"]:hover {
  background: #c82333;
}

</style>
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
