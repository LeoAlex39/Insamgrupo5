<head>
    <meta charset="UTF-8">
    <title>Panel de Gestión Escolar</title>
    <!-- Íconos de Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #f0f9ff, #ffffff);
            margin: 0;
            padding: 0;
        }
        /* Barra superior */
        .header {
            background-color: #0097a7;
            color: white;
            padding: 15px 20px;
            font-size: 22px;
            font-weight: bold;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        /* Botón cerrar sesión */
        .logout-btn {
            background: #ffffff;
            color: #0097a7;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .logout-btn:hover {
            background: #007c91;
            color: #ffffff;
        }
        /* Contenedor principal */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        /* Grid de tarjetas */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
        }
        .card i {
            font-size: 40px;
            color: #0097a7;
            margin-bottom: 10px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Barra superior con saludo y botón cerrar sesión -->
        <div class="header">
            <span>Bienvenido Docente <?= $usuario['nombreUsuario'] ?></span>
            <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout" class="logout-btn">Cerrar sesión</a>
        </div>

        <!-- Tarjetas de navegación -->
        <div class="dashboard">
            <a href="<?= BASE_URL ?>/index.php?controller=asistencia&action=index" class="card">
                <i class="fas fa-clipboard-list"></i>
                <div class="card-title">Pasar Asistencia</div>
            </a>

            <a href="<?= BASE_URL ?>/index.php?controller=conducta&action=index" class="card">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="card-title">Registrar Conducta</div>
            </a>

            <a href="<?= BASE_URL ?>/index.php?controller=horario&action=index" class="card">
                <i class="fas fa-calendar-alt"></i>
                <div class="card-title">Ver Horario</div>
            </a>
        </div>
    </div>
</body>
