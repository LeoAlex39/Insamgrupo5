<?php
// Variables esperadas: $infoClase (opcional), $alumnos, $_GET['idHorario']
?>
<h2>Registrar Conducta</h2>

<?php if (!empty($infoClase)): ?>
<div style="padding:10px;border:1px solid #ccc;margin-bottom:10px;">
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
<form method="GET" action="<?= BASE_URL ?>/index.php" style="margin-bottom:10px;">
  <input type="hidden" name="controller" value="conducta">
  <input type="hidden" name="action" value="registrar">
  <input type="hidden" name="idHorario" value="<?= (int)($_GET['idHorario'] ?? 0) ?>">
  <label>Año lectivo:</label>
  <input type="number" name="anio" value="<?= htmlspecialchars($_GET['anio'] ?? date('Y')) ?>" min="2020" max="2035">
  <button type="submit">Cambiar</button>
</form>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=conducta&action=registrar">
  <input type="hidden" name="idHorario" value="<?= (int)($_GET['idHorario'] ?? 0) ?>">

  <div style="margin:10px 0;">
    <em>Acciones rápidas:</em>
    <button type="button" onclick="marcarTodoSinIncidente()">Marcar todo SIN incidente</button>
    <button type="button" onclick="marcarTodoTipo('Tarea no entregada')">Marcar todo: Tarea no entregada</button>
    <button type="button" onclick="marcarTodoSeveridad('Baja')">Severidad: Baja</button>
    <button type="button" onclick="marcarTodoSeveridad('Media')">Severidad: Media</button>
    <button type="button" onclick="marcarTodoSeveridad('Alta')">Severidad: Alta</button>
  </div>

  <style>
    .radio-pills { display: grid; grid-template-columns: repeat(3, minmax(120px,1fr)); gap: 6px; }
    .pill { border: 1px solid #ccc; border-radius: 16px; padding: 6px 10px; display: inline-block; cursor: pointer; user-select: none; text-align:center; }
    .pill input { display: none; }
    .pill.active { background: #f0f0f0; border-color: #999; font-weight: 600; }
    @media (max-width: 900px) { .radio-pills { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 520px) { .radio-pills { grid-template-columns: repeat(1, 1fr); } }
    .muted { color:#666; font-size:12px; }
  </style>

  <table border="1" cellpadding="6" cellspacing="0" width="100%">
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

      // names para POST: tipo[id], severidad[id], detalle[id]
      $nameTipo = "tipo[$id]";
      $nameSev  = "severidad[$id]";
      $nameDet  = "detalle[$id]";
    ?>
      <tr data-alumno="<?= $id ?>">
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($al['nombre']) ?></td>
        <td><?= htmlspecialchars($al['nie']) ?></td>

        <!-- TIPO: radios exclusivos por alumno -->
        <td>
          <div class="radio-pills" role="radiogroup" aria-label="Tipo de incidente">
            <?php
              // La primera opción es "Sin incidente" => enviará vacío (para que el controlador lo ignore)
              $tipos = [
                'Sin incidente' => '',  // valor vacío
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

        <!-- SEVERIDAD: radios exclusivos por alumno -->
        <td>
          <div class="radio-pills" role="radiogroup" aria-label="Severidad">
            <?php
              $sevs = ['Baja','Media','Alta'];
              foreach ($sevs as $si => $sev):
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

        <!-- DETALLE -->
        <td>
          <input type="text" name="<?= $nameDet ?>" placeholder="Opcional (obligatorio si eliges 'Otro')" style="width:100%;">
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="6">No hay alumnos para esta clase (verifica matrícula/año).</td></tr>
    <?php endif; ?>
  </table>

  <br>
  <button type="submit">💾 Guardar incidentes de hoy</button>
  &nbsp;&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index">⬅️ Volver a mi horario</a>
</form>

<script>
  // Visual activo para cualquier grupo de radios
  document.querySelectorAll('.radio-pills').forEach(group => {
    group.addEventListener('change', e => {
      if (e.target && e.target.type === 'radio') {
        group.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
        const label = e.target.closest('.pill');
        if (label) label.classList.add('active');
      }
    });
  });

  // Marcar todo SIN incidente (pone la primera opción, que es "")
  function marcarTodoSinIncidente(){
    document.querySelectorAll('tr[data-alumno]').forEach(row => {
      const tipoRadios = row.querySelectorAll('input[type="radio"][name^="tipo["]');
      if (!tipoRadios.length) return;
      // La primera opción es "Sin incidente"
      const first = tipoRadios[0];
      first.checked = true;
      row.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
      const label = first.closest('.pill');
      if (label) label.classList.add('active');
      // Limpia el detalle
      const detalle = row.querySelector('input[name^="detalle["]');
      if (detalle) detalle.value = '';
    });
  }

  // Marcar todo un tipo determinado (si existe ese valor en la fila)
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

  // Marcar toda la severidad
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
