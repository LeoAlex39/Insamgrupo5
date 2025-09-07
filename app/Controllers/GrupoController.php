<?php
class GrupoController {
    private Grupo $model;
    public function __construct() { $this->model = new Grupo(); }

    private function requireDirector() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Subdirector','Orientador'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }
    }

    public function index() {
        $this->requireDirector();
        $items = $this->model->listar();
        include VIEW_PATH . '/grupo/index.php';
    }

    public function crear() {
        $this->requireDirector();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGrado = (int)$_POST['idGrado'];
            $idSeccion = (int)$_POST['idSeccion'];
            $idModalidad = (int)$_POST['idModalidad'];
            $alias = trim($_POST['alias'] ?? '') ?: null;

            try {
                $ok = $this->model->crear($idGrado,$idSeccion,$idModalidad,$alias);
                header('Location: ' . BASE_URL . '/index.php?controller=grupo&action=index'); exit;
            } catch (Throwable $e) {
                $error = "No se pudo crear el grupo (¿duplicado?).";
            }
        }
        $grados = $this->model->grados();
        $secciones = $this->model->secciones();
        $modalidades = $this->model->modalidades();
        include VIEW_PATH . '/grupo/crear.php';
    }

    public function editar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { http_response_code(404); echo "Grupo no encontrado"; return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGrado = (int)$_POST['idGrado'];
            $idSeccion = (int)$_POST['idSeccion'];
            $idModalidad = (int)$_POST['idModalidad'];
            $alias = trim($_POST['alias'] ?? '') ?: null;

            try {
                $this->model->actualizar($id,$idGrado,$idSeccion,$idModalidad,$alias);
                header('Location: ' . BASE_URL . '/index.php?controller=grupo&action=index'); exit;
            } catch (Throwable $e) {
                $error = "No se pudo actualizar el grupo (¿duplicado?).";
            }
        }

        $grados = $this->model->grados();
        $secciones = $this->model->secciones();
        $modalidades = $this->model->modalidades();
        include VIEW_PATH . '/grupo/editar.php';
    }

    public function eliminar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { http_response_code(404); echo "Grupo no encontrado"; return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header('Location: ' . BASE_URL . '/index.php?controller=grupo&action=index'); exit;
        } else {
            include VIEW_PATH . '/grupo/eliminar.php';
        }
    }
}
