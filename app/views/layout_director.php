<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>INSAM</title>
  <style>
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

    /* 🔹 Contenedor de contenido */
    .container {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 1.5rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <!-- Barra -->
  <nav class="navbar">
    <div class="navbar-container">
      <div class="navbar-brand">
        <span class="logo-icon">🎓</span> <strong>INSAM</strong>
      </div>
      <ul class="navbar-links">
        <li><a href="<?= BASE_URL ?>/index.php?controller=dashboard&action=director">Inicio</a></li>
        <li><a href="<?= BASE_URL ?>/index.php?controller=alumnos&action=index">Alumnos</a></li>
        <li><a href="<?= BASE_URL ?>/index.php?controller=docente&action=index">Docentes</a></li>
        <li><a href="<?= BASE_URL ?>/index.php?controller=asistencia&action=lista">Asistencias</a></li>
        <li><a href="<?= BASE_URL ?>/index.php?controller=reporte&action=index">Reportes</a></li>
        <li><a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout">Salir</a></li>
      </ul>
    </div>
  </nav>

  <!-- Contenido dinámico -->
  <div class="container">
    <?php include $viewFile; ?>
  </div>
</body>
</html>
