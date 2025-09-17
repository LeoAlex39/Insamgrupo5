<?php
// ==================== Configuración ====================
$DAYS = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

// Slot interno fino para poder representar recesos no múltiplos de 45
$slot = 5; // minutos

// Selector de bloque visual (no cambia el slot interno)
$allowedBloques = [30,45,60]; // opciones del selector
$bloqueAcademicoMin = (int)($_GET['bloque'] ?? 45);
if (!in_array($bloqueAcademicoMin, $allowedBloques, true)) {
  $bloqueAcademicoMin = 45;
}

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
  $stM = t2m(substr($h['horaInicio'],0,5));
  $enM = t2m(substr($h['horaFin'],0,5));
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
    for ($mm=$info['s']; $mm<$info['e']; $mm+=$slot) {
      if (isset($occupied[$d][$mm])) $occupied[$d][$mm] = true;
    }
  }
}

// idGrupo actual (para el link "nueva franja")
$currentGrupoId = isset($_GET['idGrupo']) ? (int)$_GET['idGrupo'] : ( (!empty($grupos) ? (int)$grupos[0]['idGrupo'] : 0) );

// ==================== Estilos ====================
?>
<style>
  /* === Base / tipografías ===
   Usa las mismas familias que has usado en otras pantallas.
   Si quieres una fuente específica (p. ej. Inter, Poppins), añade el @import o link en el head.
*/
:root{
  --accent: #0097a7;
  --accent-dark: #007c8a;
  --muted: #6b7280;
  --card-bg: #ffffff;
  --surface: #f7fbfc;
  --danger: #dc3545;
  --warning: #ffc107;
  --radius: 12px;
  --shadow-1: 0 6px 18px rgba(2,6,23,0.06);
  --shadow-2: 0 2px 8px rgba(2,6,23,0.04);
  --border: #e6eef0;
}

*{box-sizing:border-box}
body {
  font-family: Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  font-size: 15px;
  color: #1f2937;
  background: linear-gradient(180deg,#f3fbfc 0%, #ffffff 120%);
  padding: 18px;
}

/* Header + leyenda */
.headerwrap {
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}

.headerwrap h2 {
  margin:0;
  font-size:20px;
  font-weight:600;
  color:#0f172a;
}

.legend {
  color: var(--muted);
  font-size:0.9rem;
  background: #fff;
  padding:8px 12px;
  border-radius:10px;
  box-shadow: var(--shadow-2);
  border:1px solid var(--border);
}

/* Toolbar (filtros) */
.toolbar {
  display:flex;
  gap:10px;
  align-items:center;
  margin-bottom:14px;
  flex-wrap:wrap;
  background: var(--card-bg);
  padding:12px;
  border-radius: var(--radius);
  box-shadow: var(--shadow-1);
  border:1px solid var(--border);
}

.toolbar label {
  font-weight:600;
  color:#334155;
  margin-right:4px;
}

.toolbar select {
  padding:8px 10px;
  border-radius:8px;
  border:1px solid #e6eef0;
  outline:none;
  background:#fff;
  min-width:120px;
}

.toolbar select:focus {
  box-shadow: 0 0 0 4px rgba(0,151,167,0.08);
  border-color: var(--accent);
}

.toolbar button {
  background: var(--accent);
  color:#fff;
  border:none;
  padding:9px 14px;
  border-radius:8px;
  cursor:pointer;
  font-weight:600;
}

.toolbar a {
  text-decoration:none;
  padding:8px 12px;
  border-radius:8px;
  background:#eef7f8;
  color:#075b61;
  border:1px solid #dff2f3;
  font-weight:600;
}

/* Avisos (errores / no grupos) */
div[style*="color:#b00"], .no-groups {
  background:#fff6f6;
  color:#b00;
  padding:10px 12px;
  border-radius:8px;
  border:1px solid rgba(220,53,69,0.12);
  margin-bottom:12px;
  box-shadow:var(--shadow-2);
}

/* Contenedor scrollable de la tabla */
.table-wrap {
  overflow:auto;
  width:100%;
  background:transparent;
  border-radius:12px;
}

/* Tabla — cuadrícula */
.ttable {
  width:100%;
  border-collapse:separate;
  border-spacing:0;
  table-layout:fixed;
  min-width:900px; /* evita colapso excesivo; horizontal scroll en pantallas pequeñas */
  background: var(--card-bg);
  border-radius: var(--radius);
  overflow:hidden;
  box-shadow: var(--shadow-2);
  border:1px solid var(--border);
}

/* Cabeceras */
.ttable thead th {
  position:sticky;
  top:0;
  background: linear-gradient(180deg,#fbfeff,#f6fbfb);
  padding:12px 14px;
  text-align:left;
  font-weight:700;
  color:#0f172a;
  border-bottom:1px solid #eef3f4;
  z-index:3;
}

/* Columna de hora (sticky a la izquierda) */
.ttable th.timecol,
.ttable td.timecol {
  position:sticky;
  left:0;
  width:88px;
  min-width:72px;
  background:#ffffff;
  text-align:center;
  font-weight:700;
  color:#0b7285;
  z-index:4;
  border-right:1px solid #eef3f4;
}

/* Celdas */
.ttable td, .ttable th {
  padding:8px 12px;
  vertical-align:top;
  border-right:1px solid rgba(6,10,15,0.03);
}

/* Filas y slots */
.slot {
  min-height:46px;
  background:var(--card-bg);
}

/* Guía en la columna de hora (texto pequeño y menos opaco) */
.time-guide { font-size:0.82rem; color:var(--muted); }

/* Tarjeta de clase */
.classcard {
  border: 1px solid #e6eef0;
  border-left:6px solid var(--accent);
  border-radius:8px;
  padding:8px;
  margin:0;
  background: linear-gradient(180deg, #ffffff, rgba(0,151,167,0.03));
  height:100%;
  display:flex;
  flex-direction:column;
  gap:6px;
  box-shadow: 0 2px 6px rgba(2,6,23,0.04);
}

.classcard .title {
  font-weight:700;
  font-size:0.96rem;
  color:#062e35;
  line-height:1.15;
}

.classcard .meta { font-size:0.86rem; color:#334155; display:flex; flex-direction:column; gap:4px; }

.classcard .meta .tag {
  display:inline-block;
  padding:3px 8px;
  border-radius:999px;
  font-size:0.75rem;
  border:1px solid #e6eef0;
  background:#fcfeff;
  color:#0b7285;
  font-weight:600;
}

/* Acciones de cada tarjeta */
.classcard .actions {
  margin-top:auto;
  display:flex;
  gap:8px;
  align-items:center;
}

.classcard .actions a {
  text-decoration:none;
  font-size:0.85rem;
  padding:6px 8px;
  border-radius:8px;
  border:1px solid transparent;
  background:rgba(15,23,42,0.03);
  color:#0f172a;
}

.classcard .actions a[href*="editar"]{ background: linear-gradient(180deg,#fff8e6,#fff3d6); border-color: rgba(0,0,0,0.03); color:#734f07; }
.classcard .actions a[href*="eliminar"]{ background: linear-gradient(180deg,#fff2f3,#ffecec); color:var(--danger); }

/* Celdas de receso */
.breakcell {
  background:#f1f5f9;
  color:#334155;
  border-left:6px solid #9aa0a6;
  text-align:center;
  font-weight:700;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:0.95rem;
}

/* Nueva celda (enlace para crear franja) */
.newcell a {
  display:block;
  width:100%;
  height:100%;
  text-decoration:none;
  color:var(--accent);
  font-weight:700;
  text-align:center;
  border:1px dashed rgba(5,111,119,0.16);
  border-radius:8px;
  padding:10px 6px;
  background: linear-gradient(180deg, rgba(0,151,167,0.02), transparent);
}

.newcell a small { display:block; font-weight:500; color:var(--muted); font-size:0.8rem; margin-top:6px; }

/* Hovers */
.ttable tbody tr:hover td { background: rgba(3,7,18,0.01); }
.newcell a:hover { background: rgba(0,151,167,0.06); transform:translateY(-1px); transition:all .12s ease; }

/* Responsive */
@media (max-width:1000px){
  .toolbar { padding:10px; gap:8px; }
  .ttable { min-width:760px; }
  .timecol { width:72px; }
  .classcard .title { font-size:0.92rem; }
  .slot { min-height:40px; }
}

@media (max-width:700px){
  body{padding:12px}
  .headerwrap{flex-direction:column; align-items:flex-start; gap:6px;}
  .toolbar{flex-direction:column; align-items:stretch}
  .toolbar select{width:100%;}
  .toolbar a{width:100%; text-align:center;}
  .ttable{min-width:600px;}
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

  <label>Bloque:</label>
  <select name="bloque">
    <?php foreach ([30,45,60] as $opt): ?>
      <option value="<?= $opt ?>" <?= ($opt===$bloqueAcademicoMin)?'selected':'' ?>><?= $opt ?> min</option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Ver</button>

  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index<?= 
     (isset($_GET['idGrupo']) ? ('&idGrupo='.(int)$_GET['idGrupo']) : '') ?>">← Vista de lista</a>
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
      // Para guías cada N min
      $guideEvery = $bloqueAcademicoMin;
      $nextGuide  = $minM;
      ?>
      <?php foreach ($timeRows as $m): ?>
        <?php
          // Preparar guía visual en la columna de hora cada N min
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
                if (!$occupied[$d][$m]) {
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

              // Si ya está ocupado por clase o break, no pintar (lo cubre un rowspan)
              if ($occupied[$d][$m]) continue;

              // ¿Hay clase que empiece justo en este minuto?
              $cellPrinted = false;
              if (isset($byDayStart[$d][$m])) {
                $cls = $byDayStart[$d][$m][0];
                $span = $cls['span'];

                // Si la clase cruza algún break, recortar el rowspan hasta el inicio del primer break
                foreach ($breakStarts as $bs => $bi) {
                  if ($bs > $m && $bs < ($m + $span*$slot)) {
                    $span = (int)ceil(($bs - $m)/$slot);
                    break;
                  }
                }

                for ($mm = $m; $mm < $m + $span*$slot; $mm += $slot) {
                  if (isset($occupied[$d][$mm])) $occupied[$d][$mm] = true;
                }

                $h = $cls['row'];
                ?>
<td class="slot" rowspan="<?= (int)$span ?>">
  <?php
    // Color estable por asignatura (HSL desde hash)
    $asid = (int)($h['idAsignatura'] ?? 0);
    $hue = ($asid > 0) ? (crc32((string)$asid) % 360) : (crc32($h['nombreAsignatura']) % 360);
    $border = "hsl($hue, 65%, 42%)";
    $bg     = "hsl($hue, 85%, 96%)";
  ?>
  <div class="classcard" style="border-left-color: <?= $border ?>; background: <?= $bg ?>;">
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
                // Celda vacía: generar enlace para crear franja con (día, inicio, fin) precargados
                // Fin sugerido = inicio + bloque visual, pero si hay break antes, recortamos.
                $suggStart = $m;
                $suggEnd   = $m + $bloqueAcademicoMin; // minutos

                foreach ($breakStarts as $bs => $bi) {
                  if ($bs > $suggStart && $bs < $suggEnd) {
                    $suggEnd = $bs; // recorta al inicio del break
                    break;
                  }
                }
                if ($suggEnd > $maxM) $suggEnd = $maxM;

                $paramDia = urlencode($d);
                $paramIni = urlencode(m2t($suggStart));
                $paramFin = urlencode(m2t($suggEnd));
                $paramGrupo = (int)$currentGrupoId;

                $createUrl = BASE_URL . "/index.php?controller=horarioAdmin&action=crear"
                           . "&idGrupo={$paramGrupo}&dia={$paramDia}&inicio={$paramIni}&fin={$paramFin}";
                ?>
                  <td class="slot newcell">
                    <a href="<?= $createUrl ?>">+ Clase<br><small><?= htmlspecialchars(m2t($suggStart)."–".m2t($suggEnd)) ?></small></a>
                  </td>
                <?php
              }
            ?>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
