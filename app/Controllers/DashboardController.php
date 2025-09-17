<?php
class DashboardController {

    /* ===== Helpers de rol/sesión (en caso no existan globales) ===== */
    private function roleNorm(): string {
        if (!isset($_SESSION['usuario']['rol'])) return '';
        $raw = (string)$_SESSION['usuario']['rol'];
        $raw = preg_replace('/\s+/u', ' ', $raw ?? '');
        $raw = str_replace("\xC2\xA0", ' ', $raw);
        return mb_strtolower(trim($raw), 'UTF-8');
    }

    private function isAdmin(): bool {
        $r = $this->roleNorm();
        return in_array($r, ['director','subdirector','orientador'], true);
    }

    private function requireLoggedIn(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
    }

    private function requireAdmin(): void {
        $this->requireLoggedIn();
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
    }

    private function requireDocente(): void {
        $this->requireLoggedIn();
        if ($this->roleNorm() !== 'docente') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
    }

    /* ===== Acciones ===== */

    /** Dashboard para Director/Subdirector/Orientador */
    public function director() {
        $this->requireAdmin();

        $usuario  = $_SESSION['usuario'];
        $viewFile = VIEW_PATH . '/dashboard/director.php';
        // IMPORTANTE: siempre usa render() para que elija el layout correcto
        render($viewFile, compact('usuario'));
    }

    /** Dashboard para Docente */
    public function docente() {
        $this->requireDocente();

        $usuario  = $_SESSION['usuario'];
        $viewFile = VIEW_PATH . '/dashboard/docente.php';
        // render() elegirá layout.php (docente) por el rol
        render($viewFile, compact('usuario'));
    }
}
