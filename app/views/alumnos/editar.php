<!-- 🔹 Barra superior -->


<!-- 🔹 Contenido -->
<div class="container-form">
  <h2 class="titulo-seccion">✏️ Editar Alumno</h2>

  <?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" class="styled-form">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>

    <label>NIE:</label>
    <input type="text" name="nie" value="<?= htmlspecialchars($row['nie']) ?>" required>

    <label>Responsable:</label>
    <input type="text" name="responsable" value="<?= htmlspecialchars($row['responsable']) ?>" required>

    <label>Teléfono responsable:</label>
    <input type="text" name="num_responsable" value="<?= htmlspecialchars($row['num_responsable']) ?>" required>

    <label>Modalidad:</label>
    <select name="modalidad">
      <option <?= ($row['modalidad']==='Presencial'?'selected':'') ?>>Presencial</option>
      <option <?= ($row['modalidad']==='Virtual'?'selected':'') ?>>Virtual</option>
    </select>

    <label>Sección (letra):</label>
    <input type="text" name="seccion" maxlength="1" value="<?= htmlspecialchars($row['seccion']) ?>" required>

    <label>Año:</label>
    <input type="number" name="anio" value="<?= (int)$row['anio'] ?>" min="2020" max="2035" required>

    <div class="form-actions">
      <button type="submit" class="btn-guardar">💾 Guardar</button>
      <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index" class="btn-cancelar">Cancelar</a>
    </div>
  </form>
</div>

<!-- 🔹 CSS -->
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(to bottom, #f0f9ff, #ffffff);
  margin: 0;
  padding: 0;
}

/* Navbar */
.navbar {
  background-color: #0097a7;
  padding: 0.8rem 1.5rem;
}
.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 900px;
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

/* Contenedor */
.container-form {
  max-width: 600px;
  margin: 2rem auto;
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.titulo-seccion {
  margin-bottom: 1rem;
  color: #222;
}

/* Mensajes */
.error-msg {
  background: #ffe6e6;
  color: #b71c1c;
  padding: 0.8rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-weight: 500;
}

/* Formulario */
.styled-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.styled-form label {
  font-weight: 600;
  margin-bottom: 0.2rem;
}
.styled-form input,
.styled-form select {
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
  width: 100%;
  transition: border-color 0.3s;
}
.styled-form input:focus,
.styled-form select:focus {
  border-color: #0097a7;
  outline: none;
  box-shadow: 0 0 4px rgba(0,151,167,0.4);
}

/* Botones */
.form-actions {
  display: flex;
  justify-content: flex-start;
  gap: 1rem;
  margin-top: 1rem;
}
.btn-guardar {
  background-color: #0288d1;
  color: #fff;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
}
.btn-guardar:hover {
  background-color: #0277bd;
}
.btn-cancelar {
  display: inline-block;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  text-decoration: none;
  background-color: #d32f2f;
  color: #fff;
  font-weight: bold;
  transition: background 0.3s;
}
.btn-cancelar:hover {
  background-color: #b71c1c;
}
</style>
