<?php
class SeccionController {
    private Seccion $model; 
    public function __construct() { $this->model = new Seccion(); }

    private function requireDirector() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Subdirector','Orientador'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }
    }

    public function index() {
        $this->requireDirector();
        $items = $this->model->listar();
        include VIEW_PATH . '/seccion/index.php';
    }

    public function crear() {
        $this->requireDirector();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombreSeccion']);
            if ($nombre === '') { $error = "Nombre requerido"; include VIEW_PATH.'/seccion/crear.php'; return; }
            if ($this->model->crear($nombre)) {
                header("Location: ".BASE_URL."/index.php?controller=seccion&action=index"); exit;
            }
            $error = "No se pudo crear"; include VIEW_PATH.'/seccion/crear.php';
        } else { include VIEW_PATH.'/seccion/crear.php'; }
    }

    public function editar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { http_response_code(404); echo "Sección no encontrada"; return; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombreSeccion']);
            if ($nombre === '') { $error="Nombre requerido"; include VIEW_PATH.'/seccion/editar.php'; return; }
            if ($this->model->actualizar($id,$nombre)) {
                header("Location: ".BASE_URL."/index.php?controller=seccion&action=index"); exit;
            }
            $error = "No se pudo actualizar"; include VIEW_PATH.'/seccion/editar.php';
        } else { include VIEW_PATH.'/seccion/editar.php'; }
    }

    public function eliminar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { http_response_code(404); echo "Sección no encontrada"; return; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header("Location: ".BASE_URL."/index.php?controller=seccion&action=index"); exit;
        } else {
            include VIEW_PATH.'/seccion/eliminar.php';
        }
    }
}
