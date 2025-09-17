<!-- Barra de navegación -->
<!-- Barra de navegación -->
<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-brand">
      <span class="logo-icon"></span> <strong>Reporte de Asistencia por Grupo</strong>
    </div>
    <ul class="navbar-links">
    </ul>
  </div>
</nav>


<!-- Contenido -->
<h2 class="titulo-reporte"></h2>

<div class="card-reporte">
  <form method="POST" action="<?= BASE_URL ?>/index.php?controller=reporte&action=index" class="form-reporte">
    
    <div class="form-row">
      <div class="form-group">
        <label>Grado</label>
        <select name="idGrado" required>
          <option value="1">1°</option>
          <option value="2">2°</option>
          <option value="3">3°</option>
        </select>
      </div>
      <div class="form-group">
        <label>Sección</label>
        <select name="idSeccion" required>
          <option value="1">A</option>
          <option value="2">B</option>
          <option value="3">C</option>
        </select>
      </div>
      <div class="form-group">
        <label>Modalidad</label>
        <select name="idModalidad" required>
          <option value="1">Desarrollo de Software</option>
          <option value="2">General</option>
          <option value="3">Turismo</option>
        </select>
      </div>
      <div class="form-group">
        <label>Año lectivo</label>
        <input type="number" name="anio" value="<?= date('Y') ?>" min="2020" max="2035" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Desde</label>
        <input type="date" name="fechaInicio" required>
      </div>
      <div class="form-group">
        <label>Hasta</label>
        <input type="date" name="fechaFin" required>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-reporte">
         Generar Reporte
      </button>
    </div>
  </form>
</div>

<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(to bottom, #f0f9ff, #ffffff);
  margin: 0;
  padding: 0;
  height: 100;
}
/* 🔹 Navbar */
.navbar {
  background-color: #0097a7;
  padding: 17;
  margin: 0;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1100px;
  margin: 0 auto;
}

.navbar-brand {
  color: #fff;
  font-size: 1.2rem;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.navbar-links {
  list-style: none;
  display: flex;
  gap: 1.5rem;
  margin: 0;
  padding: 0;
}

.navbar-links li a {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.2s;
}

.navbar-links li a:hover {
  opacity: 0.8;
  text-decoration: underline;
}

/* 🔹 Contenido */
.titulo-reporte {
  font-weight: bold;
  margin: 2rem auto 1rem auto;
  color: #222;
  max-width: 1100px;
}

/* 🔹 Tarjeta */
.card-reporte {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  max-width: 1100px;
  margin: auto;
}

/* 🔹 Formulario */
.form-reporte {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  margin-bottom: .4rem;
  color: #333;
}

.form-group select,
.form-group input {
  padding: .6rem .8rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: border-color .2s;
}

.form-group select:focus,
.form-group input:focus {
  outline: none;
  border-color: #009688;
  box-shadow: 0 0 0 2px rgba(0,150,136,0.2);
}

/* 🔹 Botón */
.form-actions {
  text-align: right;
}

.btn-reporte {
  background-color: #0097a7;
  color: #fff;
  font-weight: 600;
  padding: .7rem 1.4rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color .3s;
}

.btn-reporte:hover {
  background-color: #0097a7;
}
</style>
