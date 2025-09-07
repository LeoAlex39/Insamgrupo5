<?php
class HorarioController {
    private $model;

    public function __construct() {
        $this->model = new Horario();
    }

    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
            header('Location: index.php?controller=auth&action=loginForm'); exit;
        }
        $docente = $_SESSION['usuario'];
        $dia = $_GET['dia'] ?? null; // filtro opcional

        $diasDisponibles = $this->model->diasDeDocente((int)$docente['idUsuario']);
        $items = $this->model->porDocente((int)$docente['idUsuario'], $dia);

        include VIEW_PATH . '/horario/index.php';
    }
}
