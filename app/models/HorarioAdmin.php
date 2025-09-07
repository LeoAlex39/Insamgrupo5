<?php
class HorarioAdmin {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /* Catálogos */
    public function grados(): array { return $this->db->query("SELECT * FROM grado ORDER BY idGrado")->fetchAll(PDO::FETCH_ASSOC); }
    public function secciones(): array { return $this->db->query("SELECT * FROM seccion ORDER BY idSeccion")->fetchAll(PDO::FETCH_ASSOC); }
    public function modalidades(): array { return $this->db->query("SELECT * FROM modalidad ORDER BY idModalidad")->fetchAll(PDO::FETCH_ASSOC); }

    /** Lista Docente+Asignatura para seleccionar en el horario */
    public function docenteAsignaturas(): array {
        $sql = "SELECT da.idDocenteAsignatura, u.idUsuario, u.nombreUsuario, a.idAsignatura, a.nombreAsignatura
                  FROM docenteasignatura da
                  JOIN usuario u ON u.idUsuario = da.idUsuario
                  JOIN asignatura a ON a.idAsignatura = da.idAsignatura
                 ORDER BY u.nombreUsuario, a.nombreAsignatura";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Consultar horario por grupo (ahora incluye idAsignatura para colorear) */
    public function listarPorGrupo(int $idGrado,int $idSeccion,int $idModalidad): array {
        $sql = "SELECT h.*, a.idAsignatura, a.nombreAsignatura, u.nombreUsuario
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

    /**
     * Validación completa:
     * - No cruza recesos/almuerzo.
     * - Duración exacta 45 o 90 min.
     * - Debe caber en un solo bloque de 90 min (ancla 07:00).
     * - Capacidad por bloque: máx 2 “unidades” (45=1, 90=2) por (grupo+día).
     * - Solape por docente y por aula (igual que antes).
     */
    public function haySolape(
        ?int $idHorario,
        int $idGrado, int $idSeccion, int $idModalidad,
        int $idDocenteAsignatura,
        string $dia, string $inicio, string $fin,
        ?string $aula
    ): array {
        $errores = [];

        // --- Helpers
        $t2m = function(string $t): int { [$h,$m]=explode(':',substr($t,0,5)); return ((int)$h)*60+(int)$m; };
        $s = $t2m($inicio);
        $e = $t2m($fin);
        $dur = $e - $s;

        // --- Reglas de recesos/almuerzo (mismos para todos los días)
        $breaks = [
          ['08:00','08:30','Receso'],
          ['10:00','10:10','Receso'],
          ['11:45','12:25','Almuerzo'],
          ['13:55','14:05','Receso'],
          ['15:35','15:45','Receso'],
        ];
        foreach ($breaks as [$bi,$bf,$label]) {
            $bs=$t2m($bi); $be=$t2m($bf);
            // Si intersecta: [s,e) ∩ [bs,be) ≠ ∅
            if (!($e <= $bs || $s >= $be)) {
                $errores[] = "Cruza {$label} {$bi}-{$bf}. Ajusta la franja.";
                break;
            }
        }

        // --- Duración exacta: 45 o 90 minutos
        if ($dur !== 45 && $dur !== 90) {
            $errores[] = "Duración inválida: debe ser 45 o 90 minutos.";
        }

        // --- Debe caber en un SOLO bloque de 90 min (ancla 07:00)
        $anchor = 7*60;    // 07:00 en minutos
        $blockLen = 90;    // 90 min por bloque
        $blockIndex = intdiv(max(0, $s - $anchor), $blockLen);
        $blockStart = $anchor + $blockIndex * $blockLen;
        $blockEnd   = $blockStart + $blockLen;

        if (!($s >= $blockStart && $e <= $blockEnd)) {
            $errores[] = "La franja debe caber dentro de un solo bloque de 90 minutos.";
        }

        // --- Capacidad por bloque (máximo 2 “unidades” por grupo+día)
        // 45 min = 1 unidad, 90 min = 2 unidades
        // Sumamos lo existente en ese bloque (grupo+día) + esta franja
        $capNueva = ($dur >= 90) ? 2 : 1;

        $sqlCap = "SELECT h.horaInicio, h.horaFin
                     FROM horario h
                    WHERE h.idGrado=? AND h.idSeccion=? AND h.idModalidad=?
                      AND h.diaSemana=?
                      AND NOT (h.horaFin   <= ? OR h.horaInicio >= ?)";
        // limitamos a lo que intersecta el bloque actual [blockStart, blockEnd)
        // y excluimos la fila en edición (si aplica)
        if ($idHorario) $sqlCap .= " AND h.idHorario <> ".(int)$idHorario;

        $stCap = $this->db->prepare($sqlCap);
        $stCap->execute([
            $idGrado,$idSeccion,$idModalidad,
            $dia,
            // fin <= blockStart  OR  inicio >= blockEnd  -> NO intersecta
            // Usamos las inversas para traer solo lo que intersecta:
            // NOT (fin <= blockStart OR inicio >= blockEnd)
            // En los placeholders anteriores usamos ? para horaFin<=? y horaInicio>=?
            // Debemos pasar blockStart como ?1 y blockEnd como ?2 en el orden correcto:
            // ARRIBA usamos: ... AND NOT (h.horaFin <= ? OR h.horaInicio >= ?)
            // por lo tanto el primer ? es blockStart, el segundo ? es blockEnd
            sprintf('%02d:%02d', intdiv($blockStart,60), $blockStart%60),
            sprintf('%02d:%02d', intdiv($blockEnd,60),   $blockEnd%60)
        ]);
        $cap = $capNueva; // inicia con la nueva
        while ($r = $stCap->fetch(PDO::FETCH_ASSOC)) {
            $rs = $t2m($r['horaInicio']);
            $re = $t2m($r['horaFin']);
            $rd = $re - $rs;
            $cap += ($rd >= 90) ? 2 : 1;
            if ($cap > 2) break;
        }
        if ($cap > 2) {
            $errores[] = "Capacidad excedida: cada bloque de 90 min admite máx 2 clases (dos de 45 o una de 90).";
        }

        // --- Solape del DOCENTE (como antes)
        $sqlDoc = "SELECT h.*
                     FROM horario h
                    WHERE h.idDocenteAsignatura=? AND h.diaSemana=?
                      AND NOT (h.horaFin <= ? OR h.horaInicio >= ?)";
        if ($idHorario) $sqlDoc .= " AND h.idHorario <> ".(int)$idHorario;
        $st = $this->db->prepare($sqlDoc);
        $st->execute([$idDocenteAsignatura,$dia,$inicio,$fin]);
        if ($st->fetch()) $errores[] = "El docente ya tiene una clase en ese horario.";

        // --- Solape de AULA (como antes)
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
