<!-- 🔹 Contenido -->
<div class="container-table">
  <h2 class="titulo-seccion">👨‍🏫 Docentes</h2>

  <!-- Barra de acciones -->
  <div class="acciones-bar">
    <a href="<?= BASE_URL ?>/index.php?controller=docente&action=crear" class="btn-nuevo">➕ Nuevo Docente</a>
    <div class="search-form">
      <input type="text" id="buscarDocente" placeholder="🔍 Buscar docente por nombre o correo...">
    </div>
  </div>

  <!-- Tabla -->
  <div class="card-table">
    <table class="styled-table" id="tablaDocentes">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td><?= (int)$it['idUsuario'] ?></td>
            <td><?= htmlspecialchars($it['nombreUsuario']) ?></td>
            <td><?= htmlspecialchars($it['correo']) ?></td>
            <td>
              <a href="<?= BASE_URL ?>/index.php?controller=docente&action=editar&id=<?= (int)$it['idUsuario'] ?>" class="btn-accion editar">✏️ Editar</a>
              <a href="<?= BASE_URL ?>/index.php?controller=docente&action=eliminar&id=<?= (int)$it['idUsuario'] ?>" class="btn-accion eliminar">🗑️ Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 📄 Paginación -->
<div class="paginacion">
  <a href="#" class="page-link">&laquo;</a>
  <a href="#" class="page-link active">1</a>
  <a href="#" class="page-link">2</a>
  <a href="#" class="page-link">3</a>
  <a href="#" class="page-link">&raquo;</a>
</div>

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

/* Paginación */
.paginacion {
  max-width: 1100px;
  margin: 1.5rem auto;
  text-align: center;
}
.page-link {
  display: inline-block;
  padding: 0.5rem 0.9rem;
  margin: 0 0.2rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  color: #0097a7;
  transition: background-color 0.3s, color 0.3s;
}
.page-link:hover {
  background-color: #0097a7;
  color: #fff;
}
.page-link.active {
  background-color: #0097a7;
  color: #fff;
  border-color: #0097a7;
}
</style>

<!-- 🔍 JS Filtro -->
<script>
document.getElementById("buscarDocente").addEventListener("keyup", function() {
  let filter = this.value.toLowerCase();
  let rows = document.querySelectorAll("#tablaDocentes tbody tr");
  rows.forEach(row => {
    let text = row.innerText.toLowerCase();
    row.style.display = text.includes(filter) ? "" : "none";
  });
});
</script>
