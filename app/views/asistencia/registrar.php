<?php 
// Variables esperadas: $infoClase, $alumnos
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Asistencia</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/asistencia.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    .bg-teal { background-color: #009688 !important; }
    .btn-teal { background-color: #009688; color: white; }
    .btn-teal:hover { background-color: #00796b; color: white; }
    .estado-pill {
      display: inline-block;
      padding: .3rem .8rem;
      margin: 0 .2rem;
      border-radius: 20px;
      border: 1px solid #ccc;
      cursor: pointer;
      user-select: none;
    }
    .estado-pill input { display: none; }
    .estado-pill.active { background-color: #009688; color: white; border-color: #009688; }
  </style>
</head>
<body>
    <div class="card shadow-lg p-4">
      <h3 class="mb-4 fw-bold">Pasar Asistencia</h3>

      <!-- Info de clase -->
      <div class="mb-4 p-3 bg-light rounded">
        <p><strong>Clase:</strong> <?= htmlspecialchars($infoClase['nombreAsignatura']) ?></p>
        <p><strong>Grado/Sección:</strong> <?= htmlspecialchars($infoClase['nombreGrado']) ?> - <?= htmlspecialchars($infoClase['nombreSeccion']) ?> | 
           <strong>Modalidad:</strong> <?= htmlspecialchars($infoClase['nombreModalidad']) ?></p>
        <p><strong>Día:</strong> <?= htmlspecialchars($infoClase['diaSemana']) ?> | 
           <strong>Hora:</strong> <?= htmlspecialchars(substr($infoClase['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($infoClase['horaFin'],0,5)) ?> | 
           <strong>Aula:</strong> <?= htmlspecialchars($infoClase['aula'] ?? '-') ?></p>
      </div>

      <!-- Formulario -->
      <form method="POST" action="<?= BASE_URL ?>/index.php?controller=asistencia&action=registrar">
        <input type="hidden" name="idHorario" value="<?= (int)$infoClase['idHorario'] ?>">

        <!-- Acciones rápidas -->
        <div class="mb-3">
          <em class="d-block mb-2">Acciones rápidas:</em>
          <button type="button" class="btn btn-sm btn-outline-success me-2" onclick="marcarTodo('Presente')">Todos Presente</button>
          <button type="button" class="btn btn-sm btn-outline-danger me-2" onclick="marcarTodo('Ausente')">Todos Ausente</button>
          <button type="button" class="btn btn-sm btn-outline-warning me-2" onclick="marcarTodo('Tarde')">Todos Tarde</button>
          <button type="button" class="btn btn-sm btn-outline-info" onclick="marcarTodo('Excusa')">Todos Excusa</button>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>NIE</th>
                <th>Estado</th>
                <th>Observación</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($alumnos)): $i=1; foreach ($alumnos as $al): 
                $id = (int)$al['id_alumno'];
                $nameGroup = "estado[$id]";
              ?>
                <tr data-alumno="<?= $id ?>">
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($al['nombre']) ?></td>
                  <td><?= htmlspecialchars($al['nie']) ?></td>
                  <td>
                    <div class="estado-grid" role="radiogroup" aria-label="Estado de <?= htmlspecialchars($al['nombre']) ?>">
                      <?php
                        $opts = ['Presente','Ausente','Tarde','Excusa'];
                        foreach ($opts as $opt):
                          $idRadio = "estado_{$id}_".strtolower($opt);
                          $checked = ($opt === 'Presente') ? 'checked' : '';
                      ?>
                        <label class="estado-pill <?= $checked ? 'active' : '' ?>" for="<?= $idRadio ?>">
                          <input type="radio" id="<?= $idRadio ?>" name="<?= $nameGroup ?>" value="<?= $opt ?>" <?= $checked ?>>
                          <?= $opt ?>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </td>
                  <td>
                    <input type="text" name="observacion[<?= $id ?>]" class="form-control" placeholder="Opcional">
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="5">No hay alumnos para esta clase (verifica matrícula/año).</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Botones -->
        <div class="text-end mt-3">
          <button type="submit" class="btn btn-teal">
            <i class="bi bi-check-circle"></i> Guardar asistencia
          </button>
          <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index" class="btn btn-outline-secondary ms-2">
            ⬅️ Volver a mi horario
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Visual activo para el radio seleccionado
    document.querySelectorAll('.estado-grid').forEach(grid => {
      grid.addEventListener('change', e => {
        if (e.target && e.target.type === 'radio') {
          grid.querySelectorAll('.estado-pill').forEach(p => p.classList.remove('active'));
          const label = e.target.closest('.estado-pill');
          if (label) label.classList.add('active');
        }
      });
    });

    // "Marcar todo" por valor
    function marcarTodo(valor){
      document.querySelectorAll('tr[data-alumno]').forEach(row => {
        const id = row.getAttribute('data-alumno');
        const radio = row.querySelector(`input[type="radio"][name="estado[${id}]"][value="${valor}"]`);
        if (radio) {
          radio.checked = true;
          row.querySelectorAll('.estado-pill').forEach(p => p.classList.remove('active'));
          const label = radio.closest('.estado-pill');
          if (label) label.classList.add('active');
        }
      });
    }
  </script>
</body>
</html>
