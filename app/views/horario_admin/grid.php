<?php
// ==================== Configuración ====================
$DAYS = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

// Slot interno fino para poder representar recesos no múltiplos de 45
$slot = 30; // minutos

// Guía visual de bloque académico (no cambia el slot interno)
$bloqueAcademicoMin = 45;

// Recesos/almuerzos (mismos cada día):
$breaks = [
  ['08:00','08:30','Receso'],
  ['10:00','10:10','Receso'],
  ['11:45','12:25','Almuerzo'],
  ['13:55','14:05','Receso'],
  ['15:35','15:45','Receso'],
];

// Helpers
function t2m(string $hhmm): int { [$h,$m] = explode(':',$hhmm); return ((int)$h)*60+(int)$m; }
function m2t(int $mins): string { $h=floor($mins/60); $m=$mins%60; return sprintf('%02d:%02d',$h,$m); }

// Calcular rango horario según datos (fallback 07:00–17:00)
$minM = 7*60;   // 07:00
$maxM = 17*60;  // 17:00
if (!empty($items)) {
  $minsStart = []; $minsEnd = [];
  foreach ($items as $h) {
    $minsStart[] = t2m(substr($h['horaInicio'],0,5));
    $minsEnd[]   = t2m(substr($h['horaFin'],0,5));
  }
  if ($minsStart) $minM = min($minM, min($minsStart));
  if ($minsEnd)   $maxM = max($maxM, max($minsEnd));
}
// ampliar ligeramente para que se vea margen
$minM = floor($minM/ $slot)*$slot;
$maxM = ceil( $maxM/ $slot)*$slot;

// Filas de tiempo
$timeRows = [];
for ($m=$minM; $m < $maxM; $m += $slot) $timeRows[] = $m;

// Indexar clases por día/horaInicio (min)
$byDayStart = [];
foreach ($items as $h) {
  $d   = $h['diaSemana'];
  $stM = t2m(substr($h['horaInicio'],0,45));
  $enM = t2m(substr($h['horaFin'],0,45));
  $span = max(1, (int)ceil(($enM - $stM) / $slot));
  $byDayStart[$d][$stM][] = ['row'=>$h,'span'=>$span,'stM'=>$stM,'enM'=>$enM];
}

// Construir mapa de recesos por minuto
$breakStarts = []; // startMinute => ['span'=>X,'label'=>'Receso'|'Almuerzo']
$breakOccupied = []; // minute => true (para marcar ocupación)
foreach ($breaks as [$ini,$fin,$label]) {
  $s = t2m($ini); $e = t2m($fin);
  $span = max(1,(int)ceil(($e-$s)/$slot));
  $breakStarts[$s] = ['span'=>$span,'label'=>$label,'s'=>$s,'e'=>$e];
  for ($mm=$s; $mm<$e; $mm+=$slot) $breakOccupied[$mm] = true;
}

// Ocupación por día y minuto (para evitar imprimir debajo de rowspans)
$occupied = [];
foreach ($DAYS as $d) {
  $occupied[$d] = [];
  foreach ($timeRows as $m) $occupied[$d][$m] = false;
}
// Marcar recesos como ocupados en TODOS los días
foreach ($DAYS as $d) {
  foreach ($breakStarts as $s => $info) {
    for ($mm=$s; $mm<$info['e']; $mm+=$slot) {
      if (isset($occupied[$d][$mm])) $occupied[$d][$mm] = true;
    }
  }
}

// ==================== Estilos ====================
?>
<style>
  .toolbar { display:flex; gap:10px; align-items:center; margin-bottom:10px; flex-wrap:wrap; }
  .toolbar select, .toolbar button, .toolbar a { padding:6px 8px; }
  .ttable { width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed; }
  .ttable th, .ttable td { border:1px solid #ddd; padding:6px; vertical-align:top; }
  .ttable th.sticky { position:sticky; top:0; background:#fafafa; z-index:2; }
  .timecol { width:76px; background:#fcfcfc; font-weight:600; text-align:center; position:sticky; left:0; z-index:1; }
  .slot { min-height:42px; background: #fff; }
  /* Guía visual cada 45 min (aplica a la columna de hora) */
  .time-guide { font-size:0.8rem; color:#666; }
  .classcard {
    border:1px solid #ccc; border-left:4px solid #5a8; border-radius:6px;
    padding:6px; margin:0; background:#fff; height:100%; display:flex; flex-direction:column; gap:4px;
  }
  .classcard .title { font-weight:600; font-size:0.95rem; line-height:1.2; }
  .classcard .meta { font-size:0.82rem; color:#333; }
  .classcard .meta .tag { display:inline-block; padding:1px 6px; border:1px solid #ddd; border-radius:999px; font-size:0.75rem; }
  .classcard .actions a { font-size:0.8rem; text-decoration:none; margin-right:8px; }
  .breakcell {
    background: #f5f7fa;
    color:#444;
    border-left:4px solid #999;
    text-align:center;
    font-weight:600;
    display:flex; align-items:center; justify-content:center;
  }
  .headerwrap { display:flex; align-items:center; gap:10px; justify-content:space-between; }
  .legend { font-size:0.85rem; color:#666; margin-bottom:6px; }
  @media (max-width: 900px){
    .timecol { width:64px; }
    .slot { min-height:36px; }
  }
</style>

<div class="headerwrap">
  <h2>Horario — vista en cuadrícula</h2>
  <div class="legend">Guía: bloque académico = <?= (int)$bloqueAcademicoMin ?> min</div>
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
      <?php
      // Para guías cada 45 min
      $guideEvery = $bloqueAcademicoMin; // 45
      $nextGuide  = $minM;
      ?>
      <?php foreach ($timeRows as $m): ?>
        <?php
          // Preparar guía visual en la columna de hora cada 45 min
          $showGuide = ($m === $nextGuide);
          if ($showGuide) $nextGuide += $guideEvery;

          // ¿Empieza un receso/almuerzo en este minuto?
          $isBreakStart = isset($breakStarts[$m]);
        ?>
        <tr>
          <th class="timecol<?= $showGuide ? ' time-guide' : '' ?>">
            <?= m2t($m) ?>
          </th>

          <?php foreach ($DAYS as $d): ?>
            <?php
              // Si hay un break que empieza ahora y este día no tiene celda aún para el break
              if ($isBreakStart) {
                $info = $breakStarts[$m];
                // Si ya está ocupado (porque otro colapso lo marcó), no pintar
                if (!$occupied[$d][$m]) {
                  // Marcar ocupación del break en este día
                  for ($mm=$info['s']; $mm<$info['e']; $mm+=$slot) {
                    if (isset($occupied[$d][$mm])) $occupied[$d][$mm] = true;
                  }
                  ?>
                  <td class="breakcell" rowspan="<?= (int)$info['span'] ?>">
                    <?= htmlspecialchars($info['label']) ?> <?= htmlspecialchars(m2t($info['s'])) ?>–<?= htmlspecialchars(m2t($info['e'])) ?>
                  </td>
                  <?php
                }
                continue; // siguiente día
              }

              // Si el minuto actual ya está ocupado por clase o break, no pintar celda (la cubre un rowspan)
              if ($occupied[$d][$m]) continue;

              // ¿Hay clase que empiece justo en este minuto?
              $cellPrinted = false;
              if (isset($byDayStart[$d][$m])) {
                // Pintar la primera clase que empiece aquí (si hubiera más, podríamos apilarlas, opcional)
                $cls = $byDayStart[$d][$m][0];
                $span = $cls['span'];

                // Si la clase cruza algún break, recortar el rowspan hasta el inicio del primer break
                foreach ($breakStarts as $bs => $bi) {
                  if ($bs > $m && $bs < ($m + $span*$slot)) {
                    $span = (int)ceil(($bs - $m)/$slot); // recorta hasta antes del break
                    break;
                  }
                }

                // Marcar ocupadas las filas siguientes por la clase
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
