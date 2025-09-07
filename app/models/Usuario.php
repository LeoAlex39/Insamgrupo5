<?php
class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($correo, $password) {
        $sql = "SELECT * FROM usuario WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contraseña'])) {
            return $user;
        }
        return false;
    }
}
