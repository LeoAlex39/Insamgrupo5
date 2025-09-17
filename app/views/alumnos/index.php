<!-- 🔹 Contenido -->
<div class="container-table">
  <h2 class="titulo-seccion">👨‍🎓 Alumnos</h2>

  <!-- Barra de acciones -->
  <div class="acciones-bar">
    <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=crear" class="btn-nuevo">➕ Nuevo Alumno</a>
    <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-form" id="formBuscarAlumnos">
      <input type="hidden" name="controller" value="alumnos">
      <input type="hidden" name="action" value="index">
      <input type="text" id="buscarAlumno" placeholder="🔍 Buscar alumno..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </form>
  </div>

  <div class="card-table">
    <!-- ⬅️ IMPORTANTE: añadir id="tablaAlumnos" -->
    <table class="styled-table" id="tablaAlumnos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>NIE</th>
          <th>Responsable</th>
          <th>Teléfono</th>
          <th>Modalidad</th>
          <th>Sección</th>
          <th>Año</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td><?= (int)$it['id_alumno'] ?></td>
            <td><?= htmlspecialchars($it['nombre']) ?></td>
            <td><?= htmlspecialchars($it['nie']) ?></td>
            <td><?= htmlspecialchars($it['responsable']) ?></td>
            <td><?= htmlspecialchars($it['num_responsable']) ?></td>
            <td><?= htmlspecialchars($it['modalidad']) ?></td>
            <td><?= htmlspecialchars($it['seccion']) ?></td>
            <td><?= htmlspecialchars($it['anio']) ?></td>
            <td>
              <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=editar&id=<?= (int)$it['id_alumno'] ?>" class="btn-accion editar">✏️ Editar</a>
              <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=eliminar&id=<?= (int)$it['id_alumno'] ?>" class="btn-accion eliminar">🗑️ Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 🔍 JS: esperar DOM, impedir submit del form y filtrar en vivo -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('buscarAlumno');
  const tabla = document.getElementById('tablaAlumnos');
  const form  = document.getElementById('formBuscarAlumnos');

  // Evita recargar la página si se presiona Enter en el buscador
  form?.addEventListener('submit', function(e){ e.preventDefault(); });

  if (!input || !tabla) return;

  const rows = tabla.querySelectorAll('tbody tr');

  input.addEventListener('input', function () {
    const filtro = this.value.toLowerCase().trim();
    rows.forEach(row => {
      const texto = row.textContent.toLowerCase();
      row.style.display = texto.includes(filtro) ? '' : 'none';
    });
  });
});
</script>


<!-- 🔹 CSS -->
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(to bottom, #f0f9ff, #ffffff);
  margin: 0;
  padding: 0;
}

/* Contenedor */
.container-table {
  max-width: 1100px;
  margin: 2rem auto;
  padding: 0 1rem;
}
.titulo-seccion {
  margin-bottom: 1rem;
  color: #222;
}

/* Barra de acciones */
.acciones-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}
.btn-nuevo {
  background-color: #0097a7;
  color: #fff;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: background-color 0.3s;
}
.btn-nuevo:hover {
  background-color: #007c8a;
}
.search-form input {
  padding: 0.5rem 0.8rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.9rem;
  width: 220px;
  transition: border-color 0.2s;
}
.search-form input:focus {
  border-color: #0097a7;
  outline: none;
  box-shadow: 0 0 0 2px rgba(0,151,167,0.2);
}

/* Tabla */
.card-table {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  overflow-x: auto;
}
.styled-table {
  width: 100%;
  border-collapse: collapse;
}
.styled-table th,
.styled-table td {
  padding: 0.8rem;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.styled-table thead {
  background-color: #0097a7;
  color: #fff;
}
.styled-table tr:hover {
  background-color: #0097a7;
}

/* Botones */
.btn-accion {
  display: inline-block;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.3s;
}
.btn-accion.editar {
  background-color: #0288d1;
  color: #fff;
}
.btn-accion.editar:hover {
  background-color: #0277bd;
}
.btn-accion.eliminar {
  background-color: #d32f2f;
  color: #fff;
  margin-left: 0.4rem;
}
.btn-accion.eliminar:hover {
  background-color: #b71c1c;
}
</style>
