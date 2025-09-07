<?php
class AsistenciaController {
    private $model;
    public function __construct() { $this->model = new Asistencia(); }

    public function index() {
        // Puedes redirigir a Horario para seleccionar clase:
        header('Location: ' . BASE_URL . '/index.php?controller=horario&action=index');
        exit;
    }

    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idHorario = (int)($_POST["idHorario"] ?? 0);
            $marcas = $_POST['estado'] ?? [];
            $obs    = $_POST['observacion'] ?? [];
            $this->model->guardarMasivo($idHorario, $marcas, $obs);

            header("Location: " . BASE_URL . "/index.php?controller=asistencia&action=lista&idHorario=".$idHorario);
            exit;
        } else {
            $idHorario = (int)($_GET['idHorario'] ?? 0);
            // Seguridad mínima: verifica que el horario pertenece al docente logueado (opcional)
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
                header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
            }

            $infoClase = $this->model->infoClase($idHorario);
            if (!$infoClase) {
                http_response_code(404);
                echo "Clase no encontrada.";
                return;
            }

            // Año lectivo (puedes permitir elegirlo desde la UI)
            $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;
            $alumnos = $this->model->alumnosPorHorario($idHorario, $anio);

            include VIEW_PATH . '/asistencia/registrar.php';
        }
    }

    public function lista() {
        $idHorario = (int)($_GET['idHorario'] ?? 0);
        $infoClase = $this->model->infoClase($idHorario);
        $datos     = $this->model->listarHoy($idHorario);
        include VIEW_PATH . '/asistencia/lista.php';
    }
}
