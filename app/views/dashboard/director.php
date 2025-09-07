<h2>Bienvenido Director <?= $usuario['nombreUsuario'] ?></h2>

<nav>
  <ul>
    <li><a href="<?= BASE_URL ?>/index.php?controller=reporte&action=index">📊 Reportes</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=docente&action=index">👨‍🏫 Docentes</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index">🎓 Alumnos</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=matricula&action=index">🗃️ Matrículas</a></li>
<li><a href="<?= BASE_URL ?>/index.php?controller=horarioAdmin&action=index">🗓️ Gestor de Horario</a></li>
<li><a href="<?= BASE_URL ?>/index.php?controller=seccion&action=index">⚙️ Secciones</a></li>
<li><a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">🧩 Grupos (Grado+Modalidad+Sección)</a></li>



    <li><a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout">🚪 Cerrar sesión</a></li>
  </ul>
</nav>
