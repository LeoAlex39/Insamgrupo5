<!-- 🔹 Barra de navegación superior -->


<!-- 🔹 Contenido -->
<div class="card-form">
  <h2 class="titulo-form">Eliminar Docente</h2>

  <p class="mensaje-eliminar">
    ¿Seguro que deseas eliminar al docente 
    <strong><?= htmlspecialchars($row['nombreUsuario']) ?></strong>?
  </p>

  <form method="POST" class="form-eliminar">
    <div class="form-actions">
      <button type="submit" class="btn-eliminar">❌ Sí, eliminar</button>
      <a href="<?= BASE_URL ?>/index.php?controller=docente&action=index" class="btn-cancelar">Cancelar</a>
    </div>
  </form>

  <?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
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

/* Card */
.card-form {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  max-width: 600px;
  margin: 2rem auto;
  text-align: center;
}
.titulo-form {
  margin-bottom: 1rem;
  color: #222;
}
.mensaje-eliminar {
  font-size: 1.05rem;
  margin-bottom: 1.5rem;
}

/* Botones */
.form-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
}
.btn-eliminar {
  background-color: #d32f2f;
  color: #fff;
  font-weight: 600;
  padding: .7rem 1.4rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color .3s;
}
.btn-eliminar:hover {
  background-color: #b71c1c;
}
.btn-cancelar {
  background-color: #9e9e9e;
  color: #fff;
  font-weight: 600;
  padding: .7rem 1.4rem;
  border-radius: 8px;
  text-decoration: none;
  display: inline-block;
  transition: background-color .3s;
}
.btn-cancelar:hover {
  background-color: #757575;
}

/* Error */
.error-msg {
  margin-top: 1rem;
  color: #b00;
  font-weight: 500;
}
</style>
