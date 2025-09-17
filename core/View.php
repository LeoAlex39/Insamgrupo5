<?php
// ... tus requires y session_start() ya existentes ...

// Asegúrate de tener session_start() SOLO aquí en index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper de renderizado centralizado:
function render(string $viewFile, array $data = []): void {
    // variables de vista
    extract($data);

    // Normalizar rol para que no fallen mayúsculas/espacios
    $rol = $_SESSION['usuario']['rol'] ?? '';
    $rol = mb_strtolower(trim((string)$rol), 'UTF-8');

    $isAdmin = in_array($rol, ['director','orientador'], true);
    $layout  = VIEW_PATH . ($isAdmin ? '/layout_director.php' : '/layout.php');

    // Para depurar visualmente qué layout cargó (opcional):
    // echo "<!-- DEBUG layout = " . basename($layout) . " rol=$rol -->";

    include $layout;
}
