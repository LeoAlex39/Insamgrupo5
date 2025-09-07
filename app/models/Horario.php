<?php
class Horario {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Lista horarios del docente logueado. Si $diaSemana no es null, filtra por día.
     * $diaSemana debe ser uno de: 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'
     */
    public function porDocente(int $idUsuario, ?string $diaSemana = null): array {
        $sql = "SELECT 
                    h.idHorario,
                    h.diaSemana, h.horaInicio, h.horaFin, h.aula,
                    a.nombreAsignatura,
                    g.nombreGrado,
                    s.nombreSeccion,
                    m.nombreModalidad
                FROM horario h
                INNER JOIN docenteasignatura da ON da.idDocenteAsignatura = h.idDocenteAsignatura
                INNER JOIN asignatura a         ON a.idAsignatura = da.idAsignatura
                INNER JOIN grado g              ON g.idGrado = h.idGrado
                INNER JOIN seccion s            ON s.idSeccion = h.idSeccion
                INNER JOIN modalidad m          ON m.idModalidad = h.idModalidad
                WHERE da.idUsuario = :idUsuario";
        $params = ['idUsuario' => $idUsuario];

        if ($diaSemana !== null && $diaSemana !== '') {
            $sql .= " AND h.diaSemana = :dia";
            $params['dia'] = $diaSemana;
        }

        $sql .= " ORDER BY 
                    FIELD(h.diaSemana,'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
                    h.horaInicio ASC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':'.$k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve los días que tiene clases el docente (para construir filtros rápidos).
     */
    public function diasDeDocente(int $idUsuario): array {
        $sql = "SELECT DISTINCT h.diaSemana
                FROM horario h
                INNER JOIN docenteasignatura da ON da.idDocenteAsignatura = h.idDocenteAsignatura
                WHERE da.idUsuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario]);
        return array_map(fn($r) => $r['diaSemana'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
