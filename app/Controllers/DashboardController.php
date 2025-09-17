<?php
class DashboardController {
    public function director() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Director') {
            header('Location: index.php?controller=auth&action=loginForm');
            exit;
        }
        $usuario = $_SESSION['usuario'];
        $viewFile = VIEW_PATH . '/dashboard/director.php';
        include VIEW_PATH . '/layout.php';
    }

    public function docente() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
            header('Location: index.php?controller=auth&action=loginForm');
            exit;
        }
        $usuario = $_SESSION['usuario'];
        $viewFile = VIEW_PATH . '/dashboard/docente.php';
        include VIEW_PATH . '/layout.php';
    }
}

