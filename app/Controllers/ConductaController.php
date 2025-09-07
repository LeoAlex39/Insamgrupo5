<?php
class ConductaController {
    private $model;
    public function __construct() { $this->model = new Conducta(); }

    // ← ESTE método evita “Acción no encontrada: index”
    public function index() {
        include VIEW_PATH . '/conducta/index.php';
    }

// app/controllers/ConductaController.php (extracto)
public function registrar() {
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // (sin cambios)
    } else {
        $idHorario = (int)($_GET['idHorario'] ?? 0);
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;

        // NUEVO: cabecera de la clase (reutiliza Asistencia::infoClase)
        $infoClase = (new Asistencia())->infoClase($idHorario);

        $alumnos = $this->model->alumnosPorHorario($idHorario, $anio);
        include VIEW_PATH . '/conducta/registrar.php';
    }
}


    public function lista() {
        $idHorario = (int)($_GET['idHorario'] ?? 0);
        $datos     = $this->model->listarPorHorarioHoy($idHorario);
        include VIEW_PATH . '/conducta/lista.php';
    }
}
