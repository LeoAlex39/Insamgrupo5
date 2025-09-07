<?php
// Variables esperadas: $infoClase, $alumnos
?>
<h2>Pasar asistencia</h2>

<div style="padding:10px;border:1px solid #ccc;margin-bottom:10px;">
  <strong>Clase:</strong> <?= htmlspecialchars($infoClase['nombreAsignatura']) ?>
  &nbsp;|&nbsp; <strong>Grado/Sección:</strong> <?= htmlspecialchars($infoClase['nombreGrado']) ?> - <?= htmlspecialchars($infoClase['nombreSeccion']) ?>
  &nbsp;|&nbsp; <strong>Modalidad:</strong> <?= htmlspecialchars($infoClase['nombreModalidad']) ?>
  <br>
  <strong>Día:</strong> <?= htmlspecialchars($infoClase['diaSemana']) ?>
  &nbsp;|&nbsp; <strong>Hora:</strong> <?= htmlspecialchars(substr($infoClase['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($infoClase['horaFin'],0,5)) ?>
  &nbsp;|&nbsp; <strong>Aula:</strong> <?= htmlspecialchars($infoClase['aula'] ?? '-') ?>
</div>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=asistencia&action=registrar">
  <input type="hidden" name="idHorario" value="<?= (int)$infoClase['idHorario'] ?>">

  <div style="margin:10px 0;">
    <em>Acciones rápidas:</em>
    <button type="button" onclick="marcarTodo('Presente')">Marcar todo Presente</button>
    <button type="button" onclick="marcarTodo('Ausente')">Marcar todo Ausente</button>
    <button type="button" onclick="marcarTodo('Tarde')">Marcar todo Tarde</button>
    <button type="button" onclick="marcarTodo('Excusa')">Marcar todo Excusa</button>
  </div>

  <style>
    .estado-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .estado-pill { border: 1px solid #ccc; border-radius: 16px; padding: 6px 10px; display: inline-block; cursor: pointer; user-select: none; text-align:center; }
    .estado-pill input { display: none; }
    .estado-pill.active { background: #f0f0f0; border-color: #999; font-weight: 600; }
    @media (max-width: 640px) { .estado-grid { grid-template-columns: repeat(2, 1fr); } }
  </style>

  <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr>
      <th>#</th>
      <th>Alumno</th>
      <th>NIE</th>
      <th>Estado</th>
      <th>Observación</th>
    </tr>

    <?php if (!empty($alumnos)): $i=1; foreach ($alumnos as $al): 
      $id = (int)$al['id_alumno'];
      $nameGroup = "estado[$id]"; // mismo name => radios exclusivos por alumno
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
          <input type="text" name="observacion[<?= $id ?>]" placeholder="Opcional" style="width:100%;">
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="5">No hay alumnos para esta clase (verifica matrícula/año).</td></tr>
    <?php endif; ?>
  </table>

  <br>
  <button type="submit">💾 Guardar asistencia de hoy</button>
  &nbsp;&nbsp;
  <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index">⬅️ Volver a mi horario</a>
</form>

<script>
  // Visual activo para el radio seleccionado
  document.querySelectorAll('.estado-grid').forEach(grid => {
    grid.addEventListener('change', e => {
      if (e.target && e.target.type === 'radio') {
        // desactivar todos los pills del grupo
        grid.querySelectorAll('.estado-pill').forEach(p => p.classList.remove('active'));
        // activar el pill del radio marcado
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
        // actualizar estilo activo
        row.querySelectorAll('.estado-pill').forEach(p => p.classList.remove('active'));
        const label = radio.closest('.estado-pill');
        if (label) label.classList.add('active');
      }
    });
  }
</script>
