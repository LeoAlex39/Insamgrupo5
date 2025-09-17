<?php
// Variables esperadas: $infoClase (opcional), $alumnos, $_GET['idHorario']
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Conducta</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .info-box {
      background: #fff;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    form {
      background: #fff;
      padding: 15px;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    form label {
      font-weight: bold;
    }

    input[type="number"], input[type="text"] {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-left: 6px;
    }

    button, a.button-link {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.2s ease-in-out;
    }

    button:hover, a.button-link:hover {
      background: #0056b3;
    }

    .acciones {
      margin: 15px 0;
    }
    .acciones button {
      margin: 3px;
      background: #6c757d;
    }
    .acciones button:hover {
      background: #5a6268;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
      font-size: 14px;
    }

    table th {
      background: #007bff;
      color: white;
    }

    .radio-pills {
      display: grid;
      grid-template-columns: repeat(3, minmax(120px,1fr));
      gap: 6px;
      margin-top: 5px;
    }
    .pill {
      border: 1px solid #ccc;
      border-radius: 16px;
      padding: 6px 10px;
      cursor: pointer;
      user-select: none;
      text-align: center;
      font-size: 13px;
      transition: all 0.2s;
    }
    .pill input { display: none; }
    .pill.active {
      background: #e6f0ff;
      border-color: #007bff;
      font-weight: bold;
      color: #007bff;
    }
    .muted {
      color:#666;
      font-size:12px;
      margin-top: 4px;
    }

    .btn-guardar {
      background: #28a745;
    }
    .btn-guardar:hover {
      background: #218838;
    }

    .volver {
      margin-left: 10px;
      color: #007bff;
      text-decoration: none;
    }
    .volver:hover {
      text-decoration: underline;
    }

    @media (max-width: 900px) {
      .radio-pills { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 520px) {
      .radio-pills { grid-template-columns: repeat(1, 1fr); }
    }
  </style>
</head>
<body>

<h2>Registrar Conducta</h2>

<?php if (!empty($infoClase)): ?>
<div class="info-box">
  <strong>Clase:</strong> <?= htmlspecialchars($infoClase['nombreAsignatura']) ?>
  &nbsp;|&nbsp; <strong>Grado/Sección:</strong> <?= htmlspecialchars($infoClase['nombreGrado']) ?> - <?= htmlspecialchars($infoClase['nombreSeccion']) ?>
  &nbsp;|&nbsp; <strong>Modalidad:</strong> <?= htmlspecialchars($infoClase['nombreModalidad']) ?>
  <br>
  <strong>Día:</strong> <?= htmlspecialchars($infoClase['diaSemana']) ?>
  &nbsp;|&nbsp; <strong>Hora:</strong> <?= htmlspecialchars(substr($infoClase['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($infoClase['horaFin'],0,5)) ?>
  &nbsp;|&nbsp; <strong>Aula:</strong> <?= htmlspecialchars($infoClase['aula'] ?? '-') ?>
</div>
<?php endif; ?>

<!-- Filtro de año (opcional) -->
<form method="GET" action="<?= BASE_URL ?>/index.php">
  <input type="hidden" name="controller" value="conducta">
  <input type="hidden" name="action" value="registrar">
  <input type="hidden" name="idHorario" value="<?= (int)($_GET['idHorario'] ?? 0) ?>">
  <label>Año lectivo:</label>
  <input type="number" name="anio" value="<?= htmlspecialchars($_GET['anio'] ?? date('Y')) ?>" min="2020" max="2035">
  <button type="submit">Cambiar</button>
</form>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=conducta&action=registrar">
  <input type="hidden" name="idHorario" value="<?= (int)($_GET['idHorario'] ?? 0) ?>">

  <div class="acciones">
    <em>Acciones rápidas:</em><br>
    <button type="button" onclick="marcarTodoSinIncidente()">Todo SIN incidente</button>
    <button type="button" onclick="marcarTodoTipo('Tarea no entregada')">Todo: Tarea no entregada</button>
    <button type="button" onclick="marcarTodoSeveridad('Baja')">Severidad: Baja</button>
    <button type="button" onclick="marcarTodoSeveridad('Media')">Severidad: Media</button>
    <button type="button" onclick="marcarTodoSeveridad('Alta')">Severidad: Alta</button>
  </div>

  <table>
    <tr>
      <th>#</th>
      <th>Alumno</th>
      <th>NIE</th>
      <th>Tipo (elige uno)</th>
      <th>Severidad</th>
      <th>Detalle</th>
    </tr>

    <?php if (!empty($alumnos)): $i=1; foreach ($alumnos as $al): 
      $id = (int)$al['id_alumno'];
      $nameTipo = "tipo[$id]";
      $nameSev  = "severidad[$id]";
      $nameDet  = "detalle[$id]";
    ?>
      <tr data-alumno="<?= $id ?>">
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($al['nombre']) ?></td>
        <td><?= htmlspecialchars($al['nie']) ?></td>

        <td>
          <div class="radio-pills" role="radiogroup">
            <?php
              $tipos = [
                'Sin incidente' => '',
                'Falta de respeto' => 'Falta de respeto',
                'Tarea no entregada' => 'Tarea no entregada',
                'Indisciplina' => 'Indisciplina',
                'Uso de celular' => 'Uso de celular',
                'Llegó tarde' => 'Llegó tarde',
                'Otro (especifique)' => '__OTRO__'
              ];
              $checkedFirst = true;
              foreach ($tipos as $label => $value):
                $idRadio = "tipo_{$id}_" . preg_replace('/[^a-z0-9_]+/i','_', strtolower($label));
                $checked = $checkedFirst ? 'checked' : '';
                $checkedFirst = false;
            ?>
              <label class="pill <?= $checked ? 'active' : '' ?>" for="<?= $idRadio ?>">
                <input type="radio" id="<?= $idRadio ?>" name="<?= $nameTipo ?>" value="<?= htmlspecialchars($value) ?>" <?= $checked ?>>
                <?= htmlspecialchars($label) ?>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="muted">Si eliges “Otro”, describe en detalle.</div>
        </td>

        <td>
          <div class="radio-pills" role="radiogroup">
            <?php
              $sevs = ['Baja','Media','Alta'];
              foreach ($sevs as $sev):
                $idSev = "sev_{$id}_".strtolower($sev);
                $checked = ($sev === 'Baja') ? 'checked' : '';
            ?>
              <label class="pill <?= $checked ? 'active' : '' ?>" for="<?= $idSev ?>">
                <input type="radio" id="<?= $idSev ?>" name="<?= $nameSev ?>" value="<?= $sev ?>" <?= $checked ?>>
                <?= $sev ?>
              </label>
            <?php endforeach; ?>
          </div>
        </td>

        <td>
          <input type="text" name="<?= $nameDet ?>" placeholder="Opcional (obligatorio si eliges 'Otro')">
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="6">No hay alumnos para esta clase (verifica matrícula/año).</td></tr>
    <?php endif; ?>
  </table>

  <br>
  <button type="submit" class="btn-guardar">💾 Guardar incidentes de hoy</button>
  <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index" class="volver">⬅️ Volver a mi horario</a>
</form>

<script>
  document.querySelectorAll('.radio-pills').forEach(group => {
    group.addEventListener('change', e => {
      if (e.target && e.target.type === 'radio') {
        group.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        const label = e.target.closest('.pill');
        if (label) label.classList.add('active');
      }
    });
  });

  function marcarTodoSinIncidente(){
    document.querySelectorAll('tr[data-alumno]').forEach(row => {
      const tipoRadios = row.querySelectorAll('input[type="radio"][name^="tipo["]');
      if (!tipoRadios.length) return;
      const first = tipoRadios[0];
      first.checked = true;
      row.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
      const label = first.closest('.pill');
      if (label) label.classList.add('active');
      const detalle = row.querySelector('input[name^="detalle["]');
      if (detalle) detalle.value = '';
    });
  }

  function marcarTodoTipo(valor){
    document.querySelectorAll('tr[data-alumno]').forEach(row => {
      const radios = row.querySelectorAll('input[type="radio"][name^="tipo["]');
      let target = null;
      radios.forEach(r => { if (r.value === valor) target = r; });
      if (target) {
        target.checked = true;
        row.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        const label = target.closest('.pill');
        if (label) label.classList.add('active');
      }
    });
  }

  function marcarTodoSeveridad(sev){
    document.querySelectorAll('tr[data-alumno]').forEach(row => {
      const radios = row.querySelectorAll(`input[type="radio"][name^="severidad["][value="${sev}"]`);
      if (radios.length) {
        const r = radios[0];
        r.checked = true;
        const group = r.closest('.radio-pills');
        if (group) {
          group.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
          const label = r.closest('.pill');
          if (label) label.classList.add('active');
        }
      }
    });
  }
</script>

</body>
</html>
