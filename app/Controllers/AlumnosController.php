<?php
class AlumnosController {
    private Alumno $model;
    public function __construct() { 
        $this->model = new Alumno(); 
    }

    private function requireDirector() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Subdirector','Orientador'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); 
            exit;
        }
    }

    public function index() {
        $this->requireDirector();
        $items = $this->model->listar();
        $viewFile = VIEW_PATH . '/alumnos/index.php';
        include VIEW_PATH . '/layout.php';
    }

    public function crear() {
        $this->requireDirector();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'nombre' => trim($_POST['nombre']),
                    'nie'    => trim($_POST['nie']),
                    'responsable' => trim($_POST['responsable']),
                    'num_responsable' => trim($_POST['num_responsable']),
                    'modalidad' => $_POST['modalidad'], // 'Presencial'|'Virtual'
                    'seccion'   => $_POST['seccion'],   // 'A'|'B'|'C'...
                    'anio'      => (int)$_POST['anio'],
                ];
                if ($data['nombre']==='' || $data['nie']==='') 
                    throw new Exception("Nombre y NIE son requeridos.");
                $this->model->crear($data);
                header('Location: ' . BASE_URL . '/index.php?controller=alumnos&action=index'); 
                exit;
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }
        $viewFile = VIEW_PATH . '/alumnos/crear.php';
        include VIEW_PATH . '/layout.php';
    }

    public function editar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { 
            http_response_code(404); 
            echo "Alumno no encontrado"; 
            return; 
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'nombre' => trim($_POST['nombre']),
                    'nie'    => trim($_POST['nie']),
                    'responsable' => trim($_POST['responsable']),
                    'num_responsable' => trim($_POST['num_responsable']),
                    'modalidad' => $_POST['modalidad'],
                    'seccion'   => $_POST['seccion'],
                    'anio'      => (int)$_POST['anio'],
                ];
                $this->model->actualizar($id, $data);
                header('Location: ' . BASE_URL . '/index.php?controller=alumnos&action=index'); 
                exit;
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $viewFile = VIEW_PATH . '/alumnos/editar.php';
        include VIEW_PATH . '/layout.php';
    }

    public function eliminar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { 
            http_response_code(404); 
            echo "Alumno no encontrado"; 
            return; 
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header('Location: ' . BASE_URL . '/index.php?controller=alumnos&action=index'); 
            exit;
        } else {
            $viewFile = VIEW_PATH . '/alumnos/eliminar.php';
            include VIEW_PATH . '/layout.php';
        }
    }
}
