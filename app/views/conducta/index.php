<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Conducta — Selecciona una clase</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    ul {
      list-style: none;
      padding: 0;
      max-width: 600px;
      margin: 0 auto 25px auto;
    }

    ul li {
      background: #fff;
      margin-bottom: 10px;
      padding: 12px 15px;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    ul li:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }

    ul li a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      font-size: 15px;
      display: block;
    }

    ul li a:hover {
      text-decoration: underline;
    }

    p {
      text-align: center;
      font-size: 14px;
      margin-top: 20px;
      color: #444;
    }

    p a {
      color: #28a745;
      text-decoration: none;
      font-weight: bold;
    }

    p a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>Registrar Conducta — Selecciona una clase</h2>

  <ul>
    <li><a href="index.php?controller=conducta&action=registrar&idHorario=1">📘 Matemática — Lunes 7:00</a></li>
    <li><a href="index.php?controller=conducta&action=registrar&idHorario=2">💻 Programación — Lunes 8:00</a></li>
    <!-- TODO: generar dinámicamente según el docente logueado -->
  </ul>

  <h2>Registrar Conducta — Selecciona una clase</h2>
  <p>
    Ve a <a href="index.php?controller=horario&action=index">📅 Mi horario</a> y usa el botón “⚠️ Registrar conducta”.
  </p>

</body>
</html>
