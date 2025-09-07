<?php
class Asistencia {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** Cabecera de la clase para mostrar en la vista */
    public function infoClase(int $idHorario): ?array {
        $sql = "SELECT 
                    h.idHorario, h.diaSemana, h.horaInicio, h.horaFin, h.aula,
                    a.nombreAsignatura,
                    g.nombreGrado, s.nombreSeccion, m.nombreModalidad
                FROM horario h
                JOIN docenteasignatura da ON da.idDocenteAsignatura = h.idDocenteAsignatura
                JOIN asignatura a ON a.idAsignatura = da.idAsignatura
                JOIN grado g ON g.idGrado = h.idGrado
                JOIN seccion s ON s.idSeccion = h.idSeccion
                JOIN modalidad m ON m.idModalidad = h.idModalidad
               WHERE h.idHorario = ?";
        $st = $this->db->prepare($sql);
        $st->execute([$idHorario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Lista alumnos por grado/sección/modalidad/año (matricula) */
    public function alumnosPorHorario(int $idHorario, ?int $anio = null): array {
        if ($anio === null) $anio = (int)date('Y');

        $sqlH = "SELECT idGrado, idSeccion, idModalidad FROM horario WHERE idHorario = ?";
        $stH  = $this->db->prepare($sqlH);
        $stH->execute([$idHorario]);
        $h = $stH->fetch(PDO::FETCH_ASSOC);
        if (!$h) return [];

        $sql = "SELECT al.*
                  FROM matricula ma
                  JOIN alumnos al ON al.id_alumno = ma.idAlumno
                 WHERE ma.idGrado = ? AND ma.idSeccion = ? AND ma.idModalidad = ? AND ma.anio = ?
                 ORDER BY al.nombre ASC";
        $st = $this->db->prepare($sql);
        $st->execute([(int)$h['idGrado'], (int)$h['idSeccion'], (int)$h['idModalidad'], (int)$anio]);
        $alumnos = $st->fetchAll(PDO::FETCH_ASSOC);

        // Fallback por sección si no hay matrícula (opcional)
        if (empty($alumnos)) {
            $secQ = $this->db->prepare("SELECT nombreSeccion FROM seccion WHERE idSeccion=?");
            $secQ->execute([(int)$h['idSeccion']]);
            $sec = $secQ->fetchColumn();
            if ($sec) {
                $st2 = $this->db->prepare("SELECT * FROM alumnos WHERE seccion = ? ORDER BY nombre ASC");
                $st2->execute([$sec]);
                $alumnos = $st2->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return $alumnos;
    }

    /** Guarda asistencia masiva del día (no duplica gracias al índice único) */
    public function guardarMasivo(int $idHorario, array $marcas, array $observaciones = []): void {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO asistencia (idAlumno, idHorario, fecha, estado, observacion)
                    VALUES (?, ?, CURDATE(), ?, ?)
                    ON DUPLICATE KEY UPDATE estado=VALUES(estado), observacion=VALUES(observacion)";
            $st = $this->db->prepare($sql);

            foreach ($marcas as $idAlumno => $estado) {
                $estado = in_array($estado, ['Presente','Ausente','Tarde','Excusa']) ? $estado : 'Presente';
                $obs    = $observaciones[$idAlumno] ?? '';
                $st->execute([(int)$idAlumno, $idHorario, $estado, $obs]);
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Listado de la asistencia de HOY, para confirmación */
    public function listarHoy(int $idHorario): array {
        $sql = "SELECT a.*, al.nombre, al.nie
                  FROM asistencia a
                  JOIN alumnos al ON al.id_alumno = a.idAlumno
                 WHERE a.idHorario = ? AND a.fecha = CURDATE()
                 ORDER BY al.nombre ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$idHorario]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
