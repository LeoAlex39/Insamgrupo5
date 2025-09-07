<?php
class ReporteController {
    private $model;
    public function __construct() { $this->model = new Reporte(); }

    public function index() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Docente'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGrado    = (int)$_POST['idGrado'];
            $idSeccion  = (int)$_POST['idSeccion'];
            $idModalidad= (int)$_POST['idModalidad'];
            $anio       = (int)$_POST['anio'];
            $fechaIni   = $_POST['fechaInicio'];
            $fechaFin   = $_POST['fechaFin'];

            $datos = $this->model->asistenciaGrupo($idGrado,$idSeccion,$idModalidad,$anio,$fechaIni,$fechaFin);
            include VIEW_PATH . '/reportes/asistencia.php';
        } else {
            include VIEW_PATH . '/reportes/formulario.php';
        }
    }
}
