<?php
class AuthController {
    private $model;

    public function __construct() {
        $this->model = new Usuario();
    }

    public function loginForm() {
        include VIEW_PATH . '/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo    = $_POST['correo'] ?? '';
            $password  = $_POST['contraseña'] ?? '';

            $user = $this->model->login($correo, $password);
            if ($user) {
                // ✅ Guardamos datos importantes en la sesión
                $_SESSION['usuario_id'] = $user['id'];   // ID del usuario
                $_SESSION['usuario']    = $user;         // Datos completos (opcional)
                $_SESSION['rol']        = $user['rol'];  // Rol del usuario

                // Redirigir según rol
                if ($user['rol'] === 'Director') {
                    header('Location: index.php?controller=dashboard&action=director');
                } elseif ($user['rol'] === 'Docente') {
                    header('Location: index.php?controller=dashboard&action=docente');
                } else {
                    echo "Rol no soportado o vacío.";
                }
                exit;
            } else {
                $error = "Correo o contraseña incorrectos.";
                include VIEW_PATH . '/auth/login.php';
            }
        } else {
            include VIEW_PATH . '/auth/login.php';
        }
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
