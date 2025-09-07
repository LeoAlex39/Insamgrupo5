<?php
class HorarioAdminController {
    private $model;
    public function __construct() { $this->model = new HorarioAdmin(); }

    private function requireDirectorODocente() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Subdirector','Docente','Orientador'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }
    }

public function index() {
    $this->requireDirectorODocente();

    $grupos = (new Grupo())->listar();

    $idGrupo = isset($_GET['idGrupo']) ? (int)$_GET['idGrupo'] : 0;
    if ($idGrupo <= 0 && !empty($grupos)) {
        $idGrupo = (int)$grupos[0]['idGrupo'];
    }

    if ($idGrupo <= 0) {
        $items = [];
        // Si no hay grupos, igual incluimos la vista que elijan
        if (($_GET['vista'] ?? '') === 'grid') {
            include VIEW_PATH.'/horario_admin/grid.php';
        } else {
            include VIEW_PATH.'/horario_admin/index.php';
        }
        return;
    }

    [$idGrado,$idSeccion,$idModalidad] = (new Grupo())->desglosarIds($idGrupo);
    $items = $this->model->listarPorGrupo($idGrado,$idSeccion,$idModalidad);

    // Selección de vista
    if (($_GET['vista'] ?? '') === 'grid') {
        include VIEW_PATH.'/horario_admin/grid.php';
    } else {
        include VIEW_PATH.'/horario_admin/index.php';
    }
}


public function crear() {
    $this->requireDirectorODocente();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idGrupo  = (int)$_POST['idGrupo'];
        [$idGrado,$idSeccion,$idModalidad] = (new Grupo())->desglosarIds($idGrupo);

        $idDocAsig = (int)$_POST['idDocenteAsignatura'];
        $dia       = $_POST['diaSemana'];
        $inicio    = $_POST['horaInicio'];
        $fin       = $_POST['horaFin'];
        $aula      = trim($_POST['aula'] ?? '');

        $err = $this->model->haySolape(null,$idDocAsig,$dia,$inicio,$fin,$aula);
        if (!empty($err)) {
            $grupos     = (new Grupo())->listar();
            $docAsig    = $this->model->docenteAsignaturas();
            $errorList  = $err;
            include VIEW_PATH.'/horario_admin/crear.php';
            return;
        }

        $ok = $this->model->crear($idGrado,$idSeccion,$idModalidad,$idDocAsig,$dia,$inicio,$fin,$aula);
        // Redirigir manteniendo el grupo seleccionado
        header('Location: '.BASE_URL."/index.php?controller=horarioAdmin&action=index&idGrupo=".$idGrupo);
        return;
    }

    // GET: mostrar formulario
    $grupos  = (new Grupo())->listar();
    $docAsig = $this->model->docenteAsignaturas();
    include VIEW_PATH.'/horario_admin/crear.php';
}

public function editar() {
    $this->requireDirectorODocente();

    $id = (int)($_GET['id'] ?? 0);
    $row = $this->model->obtener($id);
    if (!$row) { http_response_code(404); echo "Fila de horario no encontrada"; return; }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idDocAsig = (int)$_POST['idDocenteAsignatura'];
        $dia       = $_POST['diaSemana'];
        $inicio    = $_POST['horaInicio'];
        $fin       = $_POST['horaFin'];
        $aula      = trim($_POST['aula'] ?? '');

        $err = $this->model->haySolape($id,$idDocAsig,$dia,$inicio,$fin,$aula);
        if (!empty($err)) {
            $docAsig   = $this->model->docenteAsignaturas();
            $errorList = $err;
            include VIEW_PATH.'/horario_admin/editar.php';
            return;
        }

        $this->model->actualizar($id,$idDocAsig,$dia,$inicio,$fin,$aula);

        // Calcular idGrupo para volver al listado del mismo grupo
        // (No guardamos idGrupo en DB, lo reconstruimos con los tres IDs de la fila)
        $grupos = (new Grupo())->listar();
        $idGrupo = 0;
        foreach ($grupos as $g) {
            if ((int)$g['idGrado'] === (int)$row['idGrado']
             && (int)$g['idSeccion'] === (int)$row['idSeccion']
             && (int)$g['idModalidad'] === (int)$row['idModalidad']) {
                $idGrupo = (int)$g['idGrupo'];
                break;
            }
        }
        header('Location: '.BASE_URL."/index.php?controller=horarioAdmin&action=index".($idGrupo?("&idGrupo=".$idGrupo):""));
        return;
    }

    $docAsig = $this->model->docenteAsignaturas();
    include VIEW_PATH.'/horario_admin/editar.php';
}
}