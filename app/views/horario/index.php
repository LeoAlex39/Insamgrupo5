<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📅 Mi horario</title>
  <style>
    /* === Estilo general === */
    body {
      background: linear-gradient(to bottom, #f8fbfb, #eef7f7);
      font-family: "Inter", sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
    }
    .container {
      max-width: 1000px;
      margin: 50px auto;
      padding: 20px;
    }

    /* === Tarjeta === */
    .card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    h2 {
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 1.6rem;
      font-weight: 700;
      color: #222;
    }

    /* === Formulario filtros === */
    form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 25px;
      align-items: flex-end;
    }
    label {
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 5px;
      display: block;
    }
    select {
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }
    select:focus {
      border-color: #009688;
    }
    button {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      background: #009688;
      color: #fff;
      font-size: 0.95rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    button:hover {
      background: #00796b;
    }

    /* === Tabla === */
    .table-container {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.95rem;
    }
    thead {
      background: #f8f9fa;
    }
    th, td {
      padding: 12px 10px;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
    }
    th {
      font-weight: 600;
      color: #444;
    }
    tbody tr:hover {
      background: #f1fdfd;
    }

    /* === Botones de acciones === */
    .btn-outline-teal {
      display: inline-block;
      padding: 6px 12px;
      font-size: 0.85rem;
      border-radius: 6px;
      border: 1px solid #009688;
      color: #009688;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-outline-teal:hover {
      background: #009688;
      color: #fff;
    }

    /* === Texto centrado si no hay datos === */
    .text-muted {
      text-align: center;
      color: #777;
      font-style: italic;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>📅 Mi horario</h2>

      <!-- Filtros -->
      <form method="GET" action="index.php">
        <input type="hidden" name="controller" value="horario">
        <input type="hidden" name="action" value="index">

        <div>
          <label for="dia">Filtrar por día:</label>
          <select name="dia" id="dia" onchange="this.form.submit()">
            <option value="">Todos</option>
            <?php 
              $diasNombre = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
              foreach ($diasNombre as $d):
                $sel = (isset($_GET['dia']) && $_GET['dia'] === $d) ? 'selected' : '';
                $disponible = empty($diasDisponibles) ? true : in_array($d, $diasDisponibles);
            ?>
              <option value="<?= $d ?>" <?= $sel ?> <?= $disponible ? '' : 'disabled' ?>><?= $d ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <noscript><button type="submit">Aplicar</button></noscript>
      </form>

      <!-- Tabla -->
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Día</th>
              <th>Hora</th>
              <th>Asignatura</th>
              <th>Grado / Sección</th>
              <th>Modalidad</th>
              <th>Aula</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($items)): ?>
              <?php foreach ($items as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['diaSemana']) ?></td>
                  <td><?= htmlspecialchars(substr($row['horaInicio'],0,5)) ?>–<?= htmlspecialchars(substr($row['horaFin'],0,5)) ?></td>
                  <td><?= htmlspecialchars($row['nombreAsignatura']) ?></td>
                  <td><?= htmlspecialchars($row['nombreGrado']) ?> / <?= htmlspecialchars($row['nombreSeccion']) ?></td>
                  <td><?= htmlspecialchars($row['nombreModalidad']) ?></td>
                  <td><?= htmlspecialchars($row['aula'] ?? '-') ?></td>
                  <td>
                    <a href="<?= BASE_URL ?>/index.php?controller=asistencia&action=registrar&idHorario=<?= (int)$row['idHorario'] ?>" 
                       class="btn-outline-teal">
                      📝 Pasar asistencia
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-muted">No hay clases para el criterio seleccionado.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
