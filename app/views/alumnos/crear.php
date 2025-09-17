<h2 class="titulo-form">➕ Nuevo Alumno</h2>

<?php if (!empty($error)): ?>
  <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="form-alumno">
  <label>Nombre:</label>
  <input type="text" name="nombre" required>

  <label>NIE:</label>
  <input type="text" name="nie" required>

  <label>Responsable:</label>
  <input type="text" name="responsable" required>

  <label>Teléfono responsable:</label>
  <input type="text" name="num_responsable" required>

  <label>Modalidad:</label>
  <select name="modalidad">
    <option>Presencial</option>
    <option>Virtual</option>
  </select>

  <label>Sección (letra):</label>
  <input type="text" name="seccion" maxlength="1" value="A" required>

  <label>Año:</label>
  <input type="number" name="anio" value="<?= date('Y') ?>" min="2020" max="2035" required>

  <div class="form-actions">
    <button type="submit" class="btn-accion guardar">💾 Guardar</button>
    <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index" class="btn-accion cancelar">❌ Cancelar</a>
  </div>
</form>

<style>
/* 🔹 Título */
.titulo-form {
  font-weight: bold;
  color: #222;
  max-width: 600px;
  margin: 2rem auto 1rem auto;
  text-align: center;
}

/* 🔹 Errores */
.error-msg {
  background: #fdecea;
  color: #b71c1c;
  border: 1px solid #f5c6cb;
  padding: 0.7rem;
  border-radius: 8px;
  max-width: 600px;
  margin: 0 auto 1rem auto;
}

/* 🔹 Formulario */
.form-alumno {
  max-width: 600px;
  margin: 0 auto;
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-alumno label {
  font-weight: 600;
  color: #333;
  margin-bottom: 0.3rem;
}

.form-alumno input,
.form-alumno select {
  padding: 0.6rem 0.8rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: border-color 0.2s;
}

.form-alumno input:focus,
.form-alumno select:focus {
  border-color: #0097a7;
  outline: none;
  box-shadow: 0 0 0 2px rgba(0,151,167,0.2);
}

/* 🔹 Acciones */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.8rem;
  margin-top: 1rem;
}

.btn-accion {
  display: inline-block;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.3s;
  cursor: pointer;
  border: none;
}

/* Guardar */
.btn-accion.guardar {
  background-color: #0288d1;
  color: #fff;
}
.btn-accion.guardar:hover {
  background-color: #0277bd;
}

/* Cancelar */
.btn-accion.cancelar {
  background-color: #ccc;
  color: #333;
}
.btn-accion.cancelar:hover {
  background-color: #999;
  color: #fff;
}
</style>
