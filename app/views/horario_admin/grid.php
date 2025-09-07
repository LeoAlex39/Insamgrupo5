<?php
// Helpers locales
$DAYS = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

function t2m(string $hhmm): int { // "07:30" -> 450
  [$h,$m] = explode(':', $hhmm);
  return ((int)$h)*60 + (int)$m;
}
function m2t(int $mins): string { // 450 -> "07:30"
  $h = floor($mins/60);
  $m = $mins%60;
  return sprintf('%02d:%02d', $h, $m);
}

// Calcular rango horario según datos (fallback 07:00–13:00)
$slot = 30; // minutos por fila
$minM = 7*60;  // 07:00
$maxM = 13*60; // 13:00

if (!empty($items)) {
  $minsStart = [];
  $minsEnd   = [];
  foreach ($items as $h) {
    $minsStart[] = t2m(substr($h['horaInicio'],0,5));
    $minsEnd[]   = t2m(substr($h['horaFin'],0,5));
  }
  if ($minsStart) $minM = min($minM, min($minsStart));
  if ($minsEnd)   $maxM = max($maxM, max($minsEnd));
  // Ajustar a múltiplos del slot
  $minM = floor($minM / $slot) * $slot;
  $maxM = ceil($maxM / $slot) * $slot;
}
$timeRows = [];
for ($m = $minM; $m < $maxM; $m += $slot) { $timeRows[] = $m; }

// Indexar clases por día y minuto inicial
$byDayStart = [];
foreach ($items as $h) {
  $d   = $h['diaSemana'];
  $stM = t2m(substr($h['horaInicio'],0,5));
  $enM = t2m(substr($h['horaFin'],0,5));
  $span = max(1, (int)ceil(($enM - $stM) / $slot));

  $byDayStart[$d][$stM][] = [
    'row'  => $h,
    'span' => $span,
    'stM'  => $stM,
    'enM'  => $enM
  ];
}

// Para evitar pintar múltiples celdas dentro del rowspan, llevamos control por día y fila ocupada
$occupied = [];
foreach ($DAYS as $d) { $occupied[$d] = array_fill_keys($timeRows, false); }
?>
<style>
  .toolbar { display:flex; gap:10px; align-items:center; margin-bottom:10px; flex-wrap:wrap; }
  .toolbar select, .toolbar button, .toolbar a { padding:6px 8px; }
  .ttable { width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed; }
  .ttable th, .ttable td { border:1px solid #ddd; padding:6px; vertical-align:top; }
  .ttable th.sticky { position:sticky; top:0; background:#fafafa; z-index:2; }
  .timecol { width:76px; background:#fcfcfc; font-weight:600; text-align:center; position:sticky; left:0; z-index:1; }
  .slot { min-height:52px; }
  .classcard {
    border:1px solid #ccc; border-left:4px solid #888; border-radius:6px;
    padding:6px; margin:0; background:#fff; height:100%; display:flex; flex-direction:column; gap:4px;
  }
  .classcard .title { font-weight:600; font-size:0.95rem; line-height:1.2; }
  .classcard .meta { font-size:0.82rem; color:#333; }
  .classcard .meta .tag { display:inline-block; padding:1px 6px; border:1px solid #ddd; border-radius:999px; font-size:0.75rem; }
  .classcard .actions a { font-size:0.8rem; text-decoration:none; margin-right:8px; }
  .legend { font-size:0.85rem; color:#666; margin-bottom:6px; }
  .headerwrap { display:flex; align-items:center; gap:10px; justify-content:space-between; }
  @media (max-width: 900px){
    .timecol { width:64px; }
    .slot { min-height:44px; }
  }
</style>

<div class="headerwrap">
  <h2>Horario — vista en cuadrícula</h2>
  <div class="legend">Cada fila = <?= $slot ?> min</div>
</div>

<form method="GET" action="<?= BASE_URL ?>/index.php" class="toolbar">
  <input type="hidden" name="controller" value="horarioAdmin">
  <input type="hidden" name="action" value="index">
  <input type="hidden" name="vista" value="grid">

  <label>Grupo:</label>
  <select name="idGrupo">
    <?php foreach(($grupos ?? []) as $g): ?>
      <option value="<?= (int)$g['idGrupo'] ?>" <?= (isset($_GET['idGrupo']) && (int)$_GET['idGrupo']===(int)$g['idGrupo'] ? 'selected' : '') ?>>
        <?= htmlspecialchars($g['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Ver</button>

  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index<?= isset($_GET['idGrupo']) ? ('&idGrupo='.(int)$_GET['idGrupo']) : '' ?>">← Vista de lista</a>
  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=crear">➕ Nueva franja</a>
  <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">🧩 Grupos</a>
</form>

<?php if (empty($grupos)): ?>
  <div style="color:#b00;">No hay grupos. Crea uno en <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">Grupos</a>.</div>
<?php endif; ?>

<div style="overflow:auto; max-width:100%;">
  <table class="ttable">
    <thead>
      <tr>
        <th class="timecol sticky">Hora</th>
        <?php foreach ($DAYS as $d): ?>
          <th class="sticky"><?= $d ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($timeRows as $m): ?>
        <tr>
          <th class="timecol"><?= m2t($m) ?></th>
          <?php foreach ($DAYS as $d): ?>
            <?php
              if ($occupied[$d][$m]) {
                // Ya está cubierto por un rowspan de una clase anterior
                continue;
              }

              // ¿Hay clase que empiece justo en este minuto?
              $cellPrinted = false;
              if (isset($byDayStart[$d][$m])) {
                foreach ($byDayStart[$d][$m] as $cls) {
                  $span = $cls['span'];
                  // Marcar como ocupadas las filas siguientes
                  for ($mm = $m; $mm < $m + $span*$slot; $mm += $slot) {
                    if (isset($occupied[$d][$mm])) $occupied[$d][$mm] = true;
                  }
                  $h = $cls['row'];
                  ?>
                  <td class="slot" rowspan="<?= (int)$span ?>">
                    <div class="classcard">
                      <div class="title"><?= htmlspecialchars($h['nombreAsignatura']) ?></div>
                      <div class="meta">
                        <div><?= htmlspecialchars($h['nombreUsuario']) ?></div>
                        <div>
                          <span class="tag"><?= htmlspecialchars(substr($h['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($h['horaFin'],0,5)) ?></span>
                          <?php if (!empty($h['aula'])): ?>
                            &nbsp;• Aula: <strong><?= htmlspecialchars($h['aula']) ?></strong>
                          <?php endif; ?>
                        </div>
                      </div>
                      <div class="actions">
                        <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=editar&id=<?= (int)$h['idHorario'] ?>">Editar</a>
                        <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=eliminar&id=<?= (int)$h['idHorario'] ?>">Eliminar</a>
                      </div>
                    </div>
                  </td>
                  <?php
                  $cellPrinted = true;
                  // Si hay más de una que empiece a la misma hora, hacemos stack vertical simple:
                  // (ya impresa la primera, las demás se imprimen debajo en la misma celda)
                  // Para simplificar, ignoramos stacking múltiple; si lo necesitas, lo armamos.
                  break;
                }
              }
              if (!$cellPrinted) {
                // Celda vacía
                ?><td class="slot"></td><?php
              }
            ?>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
