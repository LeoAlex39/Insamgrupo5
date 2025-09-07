<?php
class Reporte {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** Devuelve alumnos de un grupo en un año lectivo */
    public function alumnosGrupo(int $idGrado, int $idSeccion, int $idModalidad, int $anio): array {
        $sql = "SELECT al.* 
                  FROM matricula ma
                  JOIN alumnos al ON al.id_alumno = ma.idAlumno
                 WHERE ma.idGrado=? AND ma.idSeccion=? AND ma.idModalidad=? AND ma.anio=?
                 ORDER BY al.nombre ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$idGrado, $idSeccion, $idModalidad, $anio]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Reporte de asistencia por grupo y rango de fechas */
    public function asistenciaGrupo(int $idGrado, int $idSeccion, int $idModalidad, int $anio, string $fechaInicio, string $fechaFin): array {
        $sql = "SELECT al.id_alumno, al.nombre, al.nie,
                       SUM(CASE WHEN a.estado='Presente' THEN 1 ELSE 0 END) as presentes,
                       SUM(CASE WHEN a.estado='Ausente' THEN 1 ELSE 0 END) as ausentes,
                       SUM(CASE WHEN a.estado='Tarde' THEN 1 ELSE 0 END) as tardes,
                       SUM(CASE WHEN a.estado='Excusa' THEN 1 ELSE 0 END) as excusas,
                       COUNT(a.idAsistencia) as total
                  FROM matricula ma
                  JOIN alumnos al ON al.id_alumno = ma.idAlumno
                  LEFT JOIN asistencia a ON a.idAlumno = al.id_alumno 
                                         AND a.idHorario IN (
                                             SELECT idHorario 
                                             FROM horario 
                                             WHERE idGrado=? AND idSeccion=? AND idModalidad=?
                                         )
                                         AND a.fecha BETWEEN ? AND ?
                 WHERE ma.idGrado=? AND ma.idSeccion=? AND ma.idModalidad=? AND ma.anio=?
              GROUP BY al.id_alumno, al.nombre, al.nie
              ORDER BY al.nombre ASC";
        $st = $this->db->prepare($sql);
        $st->execute([
            $idGrado,$idSeccion,$idModalidad,$fechaInicio,$fechaFin,
            $idGrado,$idSeccion,$idModalidad,$anio
        ]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
