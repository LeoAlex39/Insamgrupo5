<h2>Eliminar Alumno</h2>
<p>¿Eliminar a <strong><?= htmlspecialchars($row['nombre']) ?></strong>?</p>

<form method="POST" class="form-eliminar">
  <button type="submit" class="btn-accion eliminar">🗑️ Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index" class="btn-accion cancelar">❌ Cancelar</a>
</form>

<style>
.form-eliminar {
  margin-top: 1rem;
}

.btn-accion {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.3s;
  border: none;
  cursor: pointer;
}

/* 🔴 Botón eliminar (igual que en Docentes) */
.btn-accion.eliminar {
  background-color: #d32f2f;
  color: #fff;
}
.btn-accion.eliminar:hover {
  background-color: #b71c1c;
}

/* ⚪ Botón cancelar */
.btn-accion.cancelar {
  background-color: #ccc;
  color: #333;
  margin-left: 0.5rem;
}
.btn-accion.cancelar:hover {
  background-color: #999;
  color: #fff;
}
</style>
