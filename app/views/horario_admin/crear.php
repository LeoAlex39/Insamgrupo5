<h2>Nueva franja de horario</h2>
<?php if (!empty($errorList)): ?>
  <div style="color:#b00;">
    <?php foreach($errorList as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=crear">
  <fieldset>
    <legend>Grupo</legend>
    <label>Grupo (Grado — Modalidad — Sección):</label>
    <select name="idGrupo" required>
      <?php foreach(($grupos ?? []) as $g): ?>
        <option value="<?= (int)$g['idGrupo'] ?>"
          <?= (!empty($prefill['idGrupo']) && (int)$prefill['idGrupo']===(int)$g['idGrupo']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($g['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?php if (empty($grupos)): ?>
      <div style="color:#b00;">No hay grupos. Crea uno en <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">Grupos</a></div>
    <?php endif; ?>
  </fieldset>

  <fieldset>
    <legend>Clase</legend>

    <label>Día:</label>
    <select name="diaSemana">
      <?php foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $d): ?>
        <option <?= (!empty($prefill['dia']) && $prefill['dia']===$d) ? 'selected' : '' ?>><?= $d ?></option>
      <?php endforeach; ?>
    </select>

    <label>Inicio:</label>
    <input type="time" name="horaInicio" value="<?= htmlspecialchars($prefill['inicio'] ?? '07:00') ?>" required>

    <label>Fin:</label>
    <input type="time" name="horaFin" value="<?= htmlspecialchars($prefill['fin'] ?? '07:45') ?>" required>

    <label>Aula:</label>
    <input type="text" name="aula" value="<?= htmlspecialchars($prefill['aula'] ?? '') ?>" placeholder="Opcional">

    <label>Docente + Asignatura:</label>
    <select name="idDocenteAsignatura" required>
      <?php foreach ($docAsig as $da): ?>
        <option value="<?= $da['idDocenteAsignatura'] ?>">
          <?= htmlspecialchars($da['nombreUsuario'].' — '.$da['nombreAsignatura']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </fieldset>

  <br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index">Cancelar</a>
</form>
