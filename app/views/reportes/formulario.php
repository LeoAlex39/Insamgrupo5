<h2>📊 Reporte de Asistencia por Grupo</h2>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=reporte&action=index">
  <label>Grado:</label>
  <select name="idGrado" required>
    <option value="1">1°</option>
    <option value="2">2°</option>
    <option value="3">3°</option>
  </select>
  <br>

  <label>Sección:</label>
  <select name="idSeccion" required>
    <option value="1">A</option>
    <option value="2">B</option>
    <option value="3">C</option>
  </select>
  <br>

  <label>Modalidad:</label>
  <select name="idModalidad" required>
    <option value="1">Desarrollo de Software</option>
    <option value="2">General</option>
    <option value="3">Turismo</option>
  </select>
  <br>

  <label>Año lectivo:</label>
  <input type="number" name="anio" value="<?= date('Y') ?>" min="2020" max="2035" required>
  <br>

  <label>Desde:</label>
  <input type="date" name="fechaInicio" required>
  <label>Hasta:</label>
  <input type="date" name="fechaFin" required>
  <br><br>

  <button type="submit">Generar Reporte</button>
</form>
