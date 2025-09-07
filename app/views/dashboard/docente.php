<h2>Bienvenido Docente <?= $usuario['nombreUsuario'] ?></h2>

<nav>
  <ul>
    <li><a href="<?= BASE_URL ?>/index.php?controller=asistencia&action=index">📝 Pasar Asistencia</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=conducta&action=index">⚠️ Registrar Conducta</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=horario&action=index">📅 Ver Horario</a></li>
    <li><a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout">🚪 Cerrar sesión</a></li>
  </ul>
</nav>
