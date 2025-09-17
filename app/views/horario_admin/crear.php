<h2>Nueva franja de horario</h2>
<?php if (!empty($errorList)): ?>
  <div style="color:#b00;">
    <?php foreach($errorList as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
  </div>
<?php endif; ?>
<style> 
  /* Título */
h2 {
  font-size: 22px;
  font-weight: 600;
  color: #333;
  margin-bottom: 20px;
}

/* Mensajes de error */
div[style*="color:#b00;"] {
  background: #ffe5e5;
  padding: 12px;
  border-radius: 8px;
  color: #b00;
  margin-bottom: 15px;
  font-size: 14px;
  box-shadow: 0px 2px 6px rgba(0,0,0,0.05);
}

/* Formulario */
form {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  gap: 18px;
  max-width: 700px;
}

/* Fieldsets */
fieldset {
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 16px;
}

legend {
  font-weight: 600;
  color: #0097a7;
  padding: 0 8px;
}

/* Labels */
label {
  display: block;
  margin-top: 10px;
  font-weight: 500;
  color: #444;
  margin-bottom: 4px;
}

/* Inputs y selects */
input[type="text"],
input[type="time"],
select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  transition: border 0.2s ease, box-shadow 0.2s ease;
}

input:focus,
select:focus {
  border-color: #0097a7;
  box-shadow: 0 0 0 2px rgba(0,151,167,0.2);
}

/* Botones */
button[type="submit"] {
  background: #0097a7;
  color: #fff;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.3s ease;
  margin-top: 10px;
}

button[type="submit"]:hover {
  background: #007c8a;
}

form a {
  background: #f1f5f9;
  color: #333;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.3s ease;
  margin-left: 8px;
}

form a:hover {
  background: #e2e8f0;
}

</style>
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
