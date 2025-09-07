<?php
class Grupo {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** Lista con nombre compuesto: "1° — Desarrollo de Software — B" */
    public function listar(): array {
        $sql = "SELECT g.idGrupo, g.idGrado, gr.nombreGrado, g.idSeccion, s.nombreSeccion,
                       g.idModalidad, m.nombreModalidad,
                       COALESCE(g.alias, CONCAT(gr.nombreGrado, ' — ', m.nombreModalidad, ' — ', s.nombreSeccion)) AS nombre
                  FROM grupo g
                  JOIN grado gr ON gr.idGrado = g.idGrado
                  JOIN seccion s ON s.idSeccion = g.idSeccion
                  JOIN modalidad m ON m.idModalidad = g.idModalidad
                 ORDER BY gr.idGrado, m.idModalidad, s.idSeccion";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $idGrupo): ?array {
        $st = $this->db->prepare("SELECT * FROM grupo WHERE idGrupo=?");
        $st->execute([$idGrupo]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function crear(int $idGrado, int $idSeccion, int $idModalidad, ?string $alias): bool {
        $st = $this->db->prepare("INSERT INTO grupo (idGrado,idSeccion,idModalidad,alias) VALUES (?,?,?,?)");
        return $st->execute([$idGrado,$idSeccion,$idModalidad,$alias]);
    }

    public function actualizar(int $idGrupo, int $idGrado, int $idSeccion, int $idModalidad, ?string $alias): bool {
        $st = $this->db->prepare("UPDATE grupo SET idGrado=?, idSeccion=?, idModalidad=?, alias=? WHERE idGrupo=?");
        return $st->execute([$idGrado,$idSeccion,$idModalidad,$alias,$idGrupo]);
    }

    public function eliminar(int $idGrupo): bool {
        $st = $this->db->prepare("DELETE FROM grupo WHERE idGrupo=?");
        return $st->execute([$idGrupo]);
    }

    /** Catálogos de apoyo */
    public function grados(): array { return $this->db->query("SELECT * FROM grado ORDER BY idGrado")->fetchAll(PDO::FETCH_ASSOC); }
    public function secciones(): array { return $this->db->query("SELECT * FROM seccion ORDER BY idSeccion")->fetchAll(PDO::FETCH_ASSOC); }
    public function modalidades(): array { return $this->db->query("SELECT * FROM modalidad ORDER BY idModalidad")->fetchAll(PDO::FETCH_ASSOC); }

    /** Devuelve el triplete IDs del grupo (para usar en Matrícula/Horario) */
    public function desglosarIds(int $idGrupo): array {
        $st = $this->db->prepare("SELECT idGrado,idSeccion,idModalidad FROM grupo WHERE idGrupo=?");
        $st->execute([$idGrupo]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if (!$r) throw new Exception("Grupo no encontrado.");
        return [(int)$r['idGrado'], (int)$r['idSeccion'], (int)$r['idModalidad']];
    }
}
