<div class="container-table">
  <h2 class="titulo-seccion">📋 Asistencia registrada hoy</h2>

  <?php if (!empty($infoClase)): ?>
  <div class="info-clase">
    <strong>Clase:</strong> <?= htmlspecialchars($infoClase['nombreAsignatura']) ?>
    &nbsp;|&nbsp; <strong>Grado/Sección:</strong> <?= htmlspecialchars($infoClase['nombreGrado']) ?> - <?= htmlspecialchars($infoClase['nombreSeccion']) ?>
    &nbsp;|&nbsp; <strong>Día:</strong> <?= htmlspecialchars($infoClase['diaSemana']) ?>
  </div>
  <?php endif; ?>

  <div class="card-table">
    <table class="styled-table">
      <thead>
        <tr>
          <th>Alumno</th>
          <th>NIE</th>
          <th>Estado</th>
          <th>Observación</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($datos)): ?>
          <?php foreach ($datos as $d): ?>
            <tr>
              <td><?= htmlspecialchars($d['nombre']) ?></td>
              <td><?= htmlspecialchars($d['nie']) ?></td>
              <td>
                <span class="estado 
                  <?= strtolower($d['estado']) === 'presente' ? 'presente' : '' ?>
                  <?= strtolower($d['estado']) === 'ausente' ? 'ausente' : '' ?>
                  <?= strtolower($d['estado']) === 'tarde' ? 'tarde' : '' ?>
                ">
                  <?= htmlspecialchars($d['estado']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($d['observacion']) ?></td>
              <td><?= htmlspecialchars($d['fecha']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">Aún no hay registros de hoy.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="acciones-bar">
    <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index" class="btn-accion volver">⬅️ Volver a mi horario</a>
  </div>
</div>

<style>
/* 🔹 Título */
.titulo-seccion {
  margin-bottom: 1rem;
  color: #222;
  font-weight: bold;
}

/* 🔹 Info clase */
.info-clase {
  background: #f5faff;
  border-left: 5px solid #0097a7;
  padding: 0.8rem 1rem;
  margin-bottom: 1.5rem;
  border-radius: 8px;
  color: #333;
  font-size: 0.95rem;
}

/* 🔹 Contenedor tabla */
.card-table {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  overflow-x: auto;
}

/* 🔹 Tabla */
.styled-table {
  width: 100%;
  border-collapse: collapse;
}
.styled-table th,
.styled-table td {
  padding: 0.8rem;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.styled-table thead {
  background-color: #0097a7;
  color: #fff;
}
.styled-table tr:hover {
  background-color: #f5f5f5;
}

/* 🔹 Estados */
.estado {
  font-weight: 600;
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
}
.estado.presente {
  background: #e8f5e9;
  color: #2e7d32;
}
.estado.ausente {
  background: #ffebee;
  color: #c62828;
}
.estado.tarde {
  background: #fff8e1;
  color: #ef6c00;
}

/* 🔹 Botón volver */
.acciones-bar {
  margin-top: 1rem;
  text-align: right;
}
.btn-accion.volver {
  background-color: #0097a7;
  color: #fff;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: background-color 0.3s;
}
.btn-accion.volver:hover {
  background-color: #007c8a;
}
</style>
