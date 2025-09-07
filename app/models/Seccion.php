<?php
class Seccion {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function listar(): array {
        $st = $this->db->query("SELECT * FROM seccion ORDER BY idSeccion ASC");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM seccion WHERE idSeccion=?");
        $st->execute([$id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function crear(string $nombre): bool {
        $st = $this->db->prepare("INSERT INTO seccion (nombreSeccion) VALUES (?)");
        return $st->execute([$nombre]);
    }

    public function actualizar(int $id, string $nombre): bool {
        $st = $this->db->prepare("UPDATE seccion SET nombreSeccion=? WHERE idSeccion=?");
        return $st->execute([$nombre, $id]);
    }

    public function eliminar(int $id): bool {
        $st = $this->db->prepare("DELETE FROM seccion WHERE idSeccion=?");
        return $st->execute([$id]);
    }
}
