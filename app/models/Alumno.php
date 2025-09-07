<?php
class Alumno {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function listar(): array {
        $st = $this->db->query("SELECT * FROM alumnos ORDER BY nombre");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM alumnos WHERE id_alumno=?");
        $st->execute([$id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function crear(array $data): int {
        $sql = "INSERT INTO alumnos (nombre, nie, responsable, num_responsable, modalidad, seccion, anio)
                VALUES (:nombre,:nie,:responsable,:num_responsable,:modalidad,:seccion,:anio)";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':nombre'=>$data['nombre'],
            ':nie'=>$data['nie'],
            ':responsable'=>$data['responsable'],
            ':num_responsable'=>$data['num_responsable'],
            ':modalidad'=>$data['modalidad'],
            ':seccion'=>$data['seccion'],
            ':anio'=>$data['anio']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): bool {
        $sql = "UPDATE alumnos SET nombre=:nombre, nie=:nie, responsable=:responsable, num_responsable=:num_responsable,
                modalidad=:modalidad, seccion=:seccion, anio=:anio WHERE id_alumno=:id";
        $st = $this->db->prepare($sql);
        return $st->execute([
            ':nombre'=>$data['nombre'],
            ':nie'=>$data['nie'],
            ':responsable'=>$data['responsable'],
            ':num_responsable'=>$data['num_responsable'],
            ':modalidad'=>$data['modalidad'],
            ':seccion'=>$data['seccion'],
            ':anio'=>$data['anio'],
            ':id'=>$id
        ]);
    }

    public function eliminar(int $id): bool {
        $st = $this->db->prepare("DELETE FROM alumnos WHERE id_alumno=?");
        return $st->execute([$id]);
    }
}
