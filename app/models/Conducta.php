<?php
class Conducta {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function registrar(int $idAlumno, int $idHorario, string $tipo, string $severidad, string $detalle=""): bool {
        $sql = "INSERT INTO conducta (idAlumno, idHorario, fecha, tipo, severidad, detalle)
                VALUES (?, ?, CURDATE(), ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idAlumno, $idHorario, $tipo, $severidad, $detalle]);
    }

    public function listarPorHorarioHoy(int $idHorario): array {
        $sql = "SELECT c.*, al.nombre, al.nie
                  FROM conducta c
                  JOIN alumnos al ON c.idAlumno = al.id_alumno
                 WHERE c.idHorario = ? AND c.fecha = CURDATE()
                 ORDER BY FIELD(c.severidad,'Alta','Media','Baja'), al.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idHorario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorAlumno(int $idAlumno, int $limit = 50): array {
        $sql = "SELECT c.*, h.diaSemana, h.horaInicio, h.horaFin
                  FROM conducta c
                  JOIN horario h ON c.idHorario = h.idHorario
                 WHERE c.idAlumno = ?
                 ORDER BY c.fecha DESC
                 LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $idAlumno, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alumnosPorHorario(int $idHorario, ?int $anio = null): array {
        if ($anio === null) $anio = (int)date('Y');

        // Igual que en Asistencia::alumnosPorHorario
        $sql = "SELECT h.idGrado, h.idSeccion, h.idModalidad
                  FROM horario h
                 WHERE h.idHorario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idHorario]);
        $h = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$h) return [];

        $sqlAl = "SELECT al.*
                    FROM matricula ma
                    JOIN alumnos al ON al.id_alumno = ma.idAlumno
                   WHERE ma.idGrado = ? AND ma.idSeccion = ? AND ma.idModalidad = ? AND ma.anio = ?
                   ORDER BY al.nombre ASC";
        $stmt = $this->db->prepare($sqlAl);
        $stmt->execute([(int)$h['idGrado'], (int)$h['idSeccion'], (int)$h['idModalidad'], (int)$anio]);
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($alumnos)) {
            $sql2 = "SELECT s.nombreSeccion FROM seccion s WHERE s.idSeccion = ?";
            $stmt = $this->db->prepare($sql2);
            $stmt->execute([(int)$h['idSeccion']]);
            $sec = $stmt->fetchColumn();
            if ($sec) {
                $sql3 = "SELECT * FROM alumnos WHERE seccion = ? ORDER BY nombre ASC";
                $stmt = $this->db->prepare($sql3);
                $stmt->execute([$sec]);
                $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return $alumnos;
    }
}
