<?php
class HorarioAdmin {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /* Catálogos */
    public function grados(){ return $this->db->query("SELECT * FROM grado ORDER BY idGrado")->fetchAll(PDO::FETCH_ASSOC); }
    public function secciones(){ return $this->db->query("SELECT * FROM seccion ORDER BY idSeccion")->fetchAll(PDO::FETCH_ASSOC); }
    public function modalidades(){ return $this->db->query("SELECT * FROM modalidad ORDER BY idModalidad")->fetchAll(PDO::FETCH_ASSOC); }

    /** Lista Docente+Asignatura para seleccionar en el horario */
    public function docenteAsignaturas(): array {
        $sql = "SELECT da.idDocenteAsignatura, u.idUsuario, u.nombreUsuario, a.idAsignatura, a.nombreAsignatura
                  FROM docenteasignatura da
                  JOIN usuario u ON u.idUsuario = da.idUsuario
                  JOIN asignatura a ON a.idAsignatura = da.idAsignatura
                 ORDER BY u.nombreUsuario, a.nombreAsignatura";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Consultar horario por grupo */
    public function listarPorGrupo(int $idGrado,int $idSeccion,int $idModalidad): array {
        $sql = "SELECT h.*, a.nombreAsignatura, u.nombreUsuario
                  FROM horario h
                  JOIN docenteasignatura da ON da.idDocenteAsignatura = h.idDocenteAsignatura
                  JOIN asignatura a ON a.idAsignatura = da.idAsignatura
                  JOIN usuario u ON u.idUsuario = da.idUsuario
                 WHERE h.idGrado=? AND h.idSeccion=? AND h.idModalidad=?
                 ORDER BY FIELD(h.diaSemana,'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
                          h.horaInicio";
        $st = $this->db->prepare($sql);
        $st->execute([$idGrado,$idSeccion,$idModalidad]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Validar solape (docente o aula) */
    public function haySolape(?int $idHorario, int $idDocenteAsignatura, string $dia, string $inicio, string $fin, ?string $aula): array {
        $errores = [];

        // 1) Solape del DOCENTE
        $sqlDoc = "SELECT h.*
                     FROM horario h
                     WHERE h.idDocenteAsignatura=? AND h.diaSemana=? 
                       AND NOT (h.horaFin <= ? OR h.horaInicio >= ?)";
        if ($idHorario) $sqlDoc .= " AND h.idHorario <> ".(int)$idHorario;
        $st = $this->db->prepare($sqlDoc);
        $st->execute([$idDocenteAsignatura,$dia,$inicio,$fin]);
        if ($st->fetch()) $errores[] = "El docente ya tiene una clase en ese horario.";

        // 2) Solape de AULA (si se indicó)
        if ($aula && trim($aula) !== '') {
            $sqlAu = "SELECT h.* FROM horario h
                      WHERE h.aula = ? AND h.diaSemana=? 
                        AND NOT (h.horaFin <= ? OR h.horaInicio >= ?)";
            if ($idHorario) $sqlAu .= " AND h.idHorario <> ".(int)$idHorario;
            $st2 = $this->db->prepare($sqlAu);
            $st2->execute([$aula,$dia,$inicio,$fin]);
            if ($st2->fetch()) $errores[] = "El aula ya está ocupada en ese horario.";
        }

        return $errores;
    }

    /* Crear/Actualizar/Eliminar */
    public function crear(int $idGrado,int $idSeccion,int $idModalidad,int $idDocAsig,string $dia,string $inicio,string $fin,?string $aula): bool {
        $sql = "INSERT INTO horario (idGrado,idSeccion,idModalidad,idDocenteAsignatura,diaSemana,horaInicio,horaFin,aula)
                VALUES (?,?,?,?,?,?,?,?)";
        $st = $this->db->prepare($sql);
        return $st->execute([$idGrado,$idSeccion,$idModalidad,$idDocAsig,$dia,$inicio,$fin,$aula]);
    }

    public function obtener(int $idHorario): ?array {
        $st = $this->db->prepare("SELECT * FROM horario WHERE idHorario=?");
        $st->execute([$idHorario]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function actualizar(int $idHorario,int $idDocAsig,string $dia,string $inicio,string $fin,?string $aula): bool {
        $sql = "UPDATE horario
                   SET idDocenteAsignatura=?, diaSemana=?, horaInicio=?, horaFin=?, aula=?
                 WHERE idHorario=?";
        $st = $this->db->prepare($sql);
        return $st->execute([$idDocAsig,$dia,$inicio,$fin,$aula,$idHorario]);
    }

    public function eliminar(int $idHorario): bool {
        $st = $this->db->prepare("DELETE FROM horario WHERE idHorario=?");
        return $st->execute([$idHorario]);
    }
}
