<?php
class DocenteController {
    private Docente $model;
    public function __construct() { 
        $this->model = new Docente(); 
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
        $viewFile = VIEW_PATH . '/docente/index.php';
        include VIEW_PATH . '/layout.php';
    }

    public function crear() {
        $this->requireDirector();

        $asignaturas = $this->model->todasAsignaturas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombreUsuario']);
            $correo = trim($_POST['correo']);
            $pass1  = $_POST['password'] ?? '';
            $pass2  = $_POST['password2'] ?? '';
            $asigs  = isset($_POST['asignaturas']) ? array_map('intval', $_POST['asignaturas']) : [];

            try {
                if ($nombre === '' || $correo === '' || $pass1 === '' || $pass2 === '') 
                    throw new Exception("Todos los campos son obligatorios.");
                if ($pass1 !== $pass2) 
                    throw new Exception("Las contraseñas no coinciden.");

                $idUsuario = $this->model->crear($nombre, $correo, $pass1);
                $this->model->setAsignaturas($idUsuario, $asigs);

                header('Location: ' . BASE_URL . '/index.php?controller=docente&action=index'); 
                exit;
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $viewFile = VIEW_PATH . '/docente/crear.php';
        include VIEW_PATH . '/layout.php';
    }

    public function editar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { 
            http_response_code(404); 
            echo "Docente no encontrado"; 
            return; 
        }

        $asignaturas = $this->model->todasAsignaturas();
        $asigActuales = $this->model->asignaturasDeDocente($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombreUsuario']);
            $correo = trim($_POST['correo']);
            $pass1  = $_POST['password'] ?? '';
            $pass2  = $_POST['password2'] ?? '';
            $asigs  = isset($_POST['asignaturas']) ? array_map('intval', $_POST['asignaturas']) : [];

            $pwd = null;
            if ($pass1 !== '' || $pass2 !== '') {
                if ($pass1 !== $pass2) { 
                    $error = "Las contraseñas no coinciden."; 
                    $viewFile = VIEW_PATH . '/docente/editar.php';
                    include VIEW_PATH . '/layout.php';
                    return; 
                }
                $pwd = $pass1;
            }

            try {
                $this->model->actualizar($id, $nombre, $correo, $pwd);
                $this->model->setAsignaturas($id, $asigs);

                header('Location: ' . BASE_URL . '/index.php?controller=docente&action=index'); 
                exit;
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $viewFile = VIEW_PATH . '/docente/editar.php';
        include VIEW_PATH . '/layout.php';
    }

    public function eliminar() {
        $this->requireDirector();
        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->obtener($id);
        if (!$row) { 
            http_response_code(404); 
            echo "Docente no encontrado"; 
            return; 
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Limpia asignaciones primero para evitar FKs
                $this->model->setAsignaturas($id, []);
                $this->model->eliminar($id);
                header('Location: ' . BASE_URL . '/index.php?controller=docente&action=index'); 
                exit;
            } catch (Throwable $e) {
                $error = "No se pudo eliminar. Asegúrate de quitar primero sus horarios.";
                $viewFile = VIEW_PATH . '/docente/eliminar.php';
                include VIEW_PATH . '/layout.php';
                return;
            }
        } 

        $viewFile = VIEW_PATH . '/docente/eliminar.php';
        include VIEW_PATH . '/layout.php';
    }
}
