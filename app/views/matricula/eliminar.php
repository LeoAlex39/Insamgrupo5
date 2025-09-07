<h2>🗑️ Eliminar matrícula</h2>
<p>¿Seguro que deseas eliminar esta matrícula?</p>

<ul>
  <li><strong>ID:</strong> <?= (int)$row['idMatricula'] ?></li>
  <li><strong>Alumno ID:</strong> <?= (int)$row['idAlumno'] ?></li>
  <li><strong>Grado:</strong> <?= (int)$row['idGrado'] ?></li>
  <li><strong>Sección:</strong> <?= (int)$row['idSeccion'] ?></li>
  <li><strong>Modalidad:</strong> <?= (int)$row['idModalidad'] ?></li>
  <li><strong>Año:</strong> <?= (int)$row['anio'] ?></li>
</ul>

<form method="POST" action="<?= BASE_URL ?>/index.php?controller=matricula&action=destroy">
  <input type="hidden" name="idMatricula" value="<?= (int)$row['idMatricula'] ?>">
  <button type="submit">Sí, eliminar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">Cancelar</a>
</form>
