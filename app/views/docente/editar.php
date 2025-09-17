<!-- 🔹 Barra de navegación superior -->


<!-- 🔹 Contenido -->
<h2 class="titulo-form">✏️ Editar Docente</h2>

<?php if (!empty($error)): ?>
  <div class="alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card-form">
  <form method="POST" class="form-docente">
    <div class="form-group">
      <label>Nombre</label>
      <input type="text" name="nombreUsuario" value="<?= htmlspecialchars($row['nombreUsuario']) ?>" required>
    </div>

    <div class="form-group">
      <label>Correo</label>
      <input type="email" name="correo" value="<?= htmlspecialchars($row['correo']) ?>" required>
    </div>

    <div class="form-group">
      <label>Nueva contraseña (opcional)</label>
      <input type="password" name="password">
    </div>

    <div class="form-group">
      <label>Repetir nueva contraseña</label>
      <input type="password" name="password2">
    </div>

    <fieldset class="form-fieldset">
      <legend>Asignaturas</legend>
      <div class="checkbox-group">
        <?php foreach ($asignaturas as $a): ?>
          <label class="checkbox-item">
            <input type="checkbox" name="asignaturas[]" value="<?= (int)$a['idAsignatura'] ?>"
              <?= in_array((int)$a['idAsignatura'], $asigActuales, true) ? 'checked' : '' ?>>
            <?= htmlspecialchars($a['nombreAsignatura']) ?>
          </label>
        <?php endforeach; ?>
      </div>
    </fieldset>

    <div class="form-actions">
      <button type="submit" class="btn-guardar">💾 Guardar</button>
      <a href="<?= BASE_URL ?>/index.php?controller=docente&action=index" class="btn-cancelar">Cancelar</a>
    </div>
  </form>
</div>

<style>
/* 🔹 Fondo y fuente */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(to bottom, #f0f9ff, #ffffff);
  margin: 0;
  padding: 0;
}

/* 🔹 Navbar */
.navbar {
  background-color: #0097a7;
  padding: 0.8rem 1.5rem;
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

.logo-icon {
  font-size: 1.3rem;
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

/* 🔹 Título */
.titulo-form {
  font-weight: bold;
  color: #222;
  max-width: 1100px;
  margin: 2rem auto 1rem auto;
}

/* 🔹 Alertas */
.alert-error {
  max-width: 1100px;
  margin: 1rem auto;
  background: #ffebee;
  color: #c62828;
  padding: 0.8rem 1rem;
  border-left: 5px solid #c62828;
  border-radius: 6px;
  font-weight: 500;
}

/* 🔹 Tarjeta del formulario */
.card-form {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  max-width: 600px;
  margin: auto;
}

/* 🔹 Formulario */
.form-docente {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 0.4rem;
  color: #333;
}

.form-group input {
  padding: 0.6rem 0.9rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: border-color .2s;
}

.form-group input:focus {
  outline: none;
  border-color: #009688;
  box-shadow: 0 0 0 2px rgba(0,150,136,0.2);
}

/* 🔹 Fieldset */
.form-fieldset {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
}

.form-fieldset legend {
  font-weight: bold;
  color: #0097a7;
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 0.5rem;
}

.checkbox-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.95rem;
}

/* 🔹 Botones */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-guardar {
  background-color: #0097a7;
  color: #fff;
  font-weight: 600;
  padding: 0.7rem 1.4rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color .3s;
  text-decoration: none;
}

.btn-guardar:hover {
  background-color: #007c8a;
}

.btn-cancelar {
  background-color: #ccc;
  color: #333;
  font-weight: 600;
  padding: 0.7rem 1.4rem;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.3s;
}

.btn-cancelar:hover {
  background-color: #b3b3b3;
}
</style>
