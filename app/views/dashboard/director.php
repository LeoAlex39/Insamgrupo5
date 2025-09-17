<?php
// Puedes incluir validaciones de sesión aquí si lo necesitas
// session_start(); if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
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
            justify-content: space-between; /* Panel a la izquierda, botón a la derecha */
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
        /* Grid de tarjetas */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
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

    <!-- Header con botón cerrar sesión -->
    <div class="header">
        <span>Panel de Gestión Escolar</span>
        <ul style="list-style:none; margin:0; padding:0;">
            <li style="display:inline;">
                <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout" class="logout-btn"> Cerrar sesión</a>
            </li>
        </ul>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <a href="?controller=reporte&action=index" class="card">
            <i class="fas fa-chart-bar"></i>
            <div class="card-title">Reporte</div>
        </a>
        <a href="?controller=docente&action=index" class="card">
            <i class="fas fa-graduation-cap"></i>
            <div class="card-title">Docentes</div>
        </a>
        <a href="?controller=alumnos&action=index" class="card">
            <i class="fas fa-users"></i>
            <div class="card-title">Alumnos</div>
        </a>
        <a href="?controller=matricula&action=index" class="card">
            <i class="fas fa-clipboard-list"></i>
            <div class="card-title">Matrículas</div>
        </a>
        <a href="?controller=horarioAdmin&action=index" class="card">
            <i class="fas fa-calendar-alt"></i>
            <div class="card-title">Gestor de Horarios</div>
        </a>
        <a href="?controller=grupo&action=index" class="card">
            <i class="fas fa-user-friends"></i>
            <div class="card-title">Grupos</div>
        </a>
        <a href="?controller=horarioadmin&action=index&vista=grid" class="card">
            <i class="fas fa-table"></i>
            <div class="card-title">Vista de Horarios</div>
        </a>
    </div>

</body>
</html>

