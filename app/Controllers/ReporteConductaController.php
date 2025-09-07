<?php
class ReporteConductaController {
    private $model;
    public function __construct() { $this->model = new ReporteConducta(); }

    public function index() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Docente'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGrado     = (int)$_POST['idGrado'];
            $idSeccion   = (int)$_POST['idSeccion'];
            $idModalidad = (int)$_POST['idModalidad'];
            $anio        = (int)$_POST['anio'];
            $fechaIni    = $_POST['fechaInicio'];
            $fechaFin    = $_POST['fechaFin'];

            $resumen = $this->model->resumenPorAlumno($idGrado,$idSeccion,$idModalidad,$anio,$fechaIni,$fechaFin);
            $tipos   = $this->model->tiposGrupo($idGrado,$idSeccion,$idModalidad,$fechaIni,$fechaFin);

            include VIEW_PATH . '/reportes_conducta/resultados.php';
        } else {
            include VIEW_PATH . '/reportes_conducta/formulario.php';
        }
    }

    /** Exportar CSV del mismo resumen por alumno */
    public function csv() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Docente'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }

        // filtros por GET para compartir link desde resultados
        $idGrado     = (int)($_GET['idGrado'] ?? 0);
        $idSeccion   = (int)($_GET['idSeccion'] ?? 0);
        $idModalidad = (int)($_GET['idModalidad'] ?? 0);
        $anio        = (int)($_GET['anio'] ?? date('Y'));
        $fechaIni    = $_GET['fechaInicio'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fechaFin'] ?? date('Y-m-d');

        $resumen = $this->model->resumenPorAlumno($idGrado,$idSeccion,$idModalidad,$anio,$fechaIni,$fechaFin);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_conducta_'.$anio.'_'.$fechaIni.'_'.$fechaFin.'.csv');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Alumno','NIE','Baja','Media','Alta','Total']);
        foreach ($resumen as $r) {
            fputcsv($out, [
                $r['nombre'], $r['nie'], $r['bajas'], $r['medias'], $r['altas'], $r['total']
            ]);
        }
        fclose($out);
        exit;
    }
}
