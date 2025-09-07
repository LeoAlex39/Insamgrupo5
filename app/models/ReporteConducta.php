<?php
class ReporteConducta {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** Lista alumnos del grupo (grado/sección/modalidad/año) */
    public function alumnosGrupo(int $idGrado, int $idSeccion, int $idModalidad, int $anio): array {
        $sql = "SELECT al.*
                  FROM matricula ma
                  JOIN alumnos al ON al.id_alumno = ma.idAlumno
                 WHERE ma.idGrado=? AND ma.idSeccion=? AND ma.idModalidad=? AND ma.anio=?
                 ORDER BY al.nombre ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$idGrado,$idSeccion,$idModalidad,$anio]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Resumen por alumno: conteo por severidad y total de incidentes
     * (Rango de fechas y "grupo" determinan los horarios válidos)
     */
    public function resumenPorAlumno(
        int $idGrado, int $idSeccion, int $idModalidad, int $anio,
        string $fechaInicio, string $fechaFin
    ): array {
        $sql = "SELECT al.id_alumno, al.nombre, al.nie,
                       SUM(CASE WHEN c.severidad='Baja'  THEN 1 ELSE 0 END) AS bajas,
                       SUM(CASE WHEN c.severidad='Media' THEN 1 ELSE 0 END) AS medias,
                       SUM(CASE WHEN c.severidad='Alta'  THEN 1 ELSE 0 END) AS altas,
                       COUNT(c.idConducta) AS total
                  FROM matricula ma
                  JOIN alumnos al ON al.id_alumno = ma.idAlumno
                  LEFT JOIN conducta c 
                         ON c.idAlumno = al.id_alumno
                        AND c.idHorario IN (
                            SELECT idHorario FROM horario 
                             WHERE idGrado=? AND idSeccion=? AND idModalidad=?
                        )
                        AND c.fecha BETWEEN ? AND ?
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

    /** Distribución de tipos de incidente para el grupo (en fechas) */
    public function tiposGrupo(
        int $idGrado, int $idSeccion, int $idModalidad,
        string $fechaInicio, string $fechaFin
    ): array {
        $sql = "SELECT c.tipo, COUNT(*) AS cantidad
                  FROM conducta c
                 WHERE c.idHorario IN (
                         SELECT idHorario FROM horario 
                          WHERE idGrado=? AND idSeccion=? AND idModalidad=?
                       )
                   AND c.fecha BETWEEN ? AND ?
              GROUP BY c.tipo
              ORDER BY cantidad DESC, c.tipo ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$idGrado,$idSeccion,$idModalidad,$fechaInicio,$fechaFin]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
