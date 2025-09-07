<?php
define('ROOT', dirname(__DIR__));
define('APP_PATH', ROOT . '/app');
define('CORE_PATH', ROOT . '/core');
define('VIEW_PATH', APP_PATH . '/views');

// --- NUEVO: URL base absoluta para generar enlaces seguros ---
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/'); // normalmente /Pruebas/public
define('BASE_URL', $scheme . '://' . $host . $basePath);
// Ejemplo: http://localhost/Pruebas/public


// Iniciar sesión SOLO una vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar core y clases
require_once CORE_PATH . '/Database.php';

// (Requiere tus modelos y controladores; ajusta nombres reales)
require_once APP_PATH . '/models/Conducta.php';
require_once APP_PATH . '/models/Usuario.php';
require_once APP_PATH . '/models/Horario.php';
require_once APP_PATH . '/models/Asistencia.php';
require_once APP_PATH . '/models/Reporte.php';
require_once APP_PATH . '/models/ReporteConducta.php';
require_once APP_PATH . '/models/Matricula.php';
require_once APP_PATH.'/models/Seccion.php';
require_once APP_PATH.'/models/HorarioAdmin.php';
require_once APP_PATH.'/models/Grupo.php';
require_once APP_PATH.'/models/Docente.php';
require_once APP_PATH.'/models/Alumno.php';




require_once APP_PATH . '/controllers/AuthController.php';
require_once APP_PATH . '/controllers/DashboardController.php';
require_once APP_PATH . '/controllers/AsistenciaController.php';
require_once APP_PATH . '/controllers/ConductaController.php';
require_once APP_PATH . '/controllers/HorarioController.php';
require_once APP_PATH . '/controllers/ReporteController.php';
require_once APP_PATH . '/controllers/ReporteConductaController.php';
require_once APP_PATH . '/controllers/MatriculaController.php';
require_once APP_PATH.'/controllers/SeccionController.php';
require_once APP_PATH.'/controllers/HorarioAdminController.php';
require_once APP_PATH.'/controllers/GrupoController.php';
require_once APP_PATH.'/controllers/DocenteController.php';
require_once APP_PATH.'/controllers/AlumnosController.php';




// Router simple
$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'loginForm';

$controllerName = ucfirst($controller).'Controller';
if (class_exists($controllerName)) {
    $ctrl = new $controllerName();
    if (method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        http_response_code(404);
        echo "Acción no encontrada: $action";
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado: $controllerName";
}
