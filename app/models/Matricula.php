<?php
class Matricula {
    private $db;

    public function __construct() {
        // Debes tener una clase Database con getInstance() que devuelva PDO
        $this->db = Database::getInstance();
    }

    /* ==========================================================
     *                 Catálogos / Datos base
     * ========================================================== */
    public function alumnosAll(): array {
        $sql = "SELECT id_alumno, nombre, nie FROM alumnos ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function grados(): array {
        $sql = "SELECT idGrado, nombreGrado FROM grado ORDER BY idGrado ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function secciones(): array {
        $sql = "SELECT idSeccion, nombreSeccion FROM seccion ORDER BY idSeccion ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modalidades(): array {
        $sql = "SELECT idModalidad, nombreModalidad FROM modalidad ORDER BY idModalidad ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================================
     *                   Listado / Búsqueda
     * ========================================================== */
    public function listar(?int $anio=null, ?int $idGrado=null, ?int $idSeccion=null, ?int $idModalidad=null): array {
        $where  = [];
        $params = [];

        if ($anio        !== null) { $where[] = "ma.anio = ?";        $params[] = $anio; }
        if ($idGrado     !== null) { $where[] = "ma.idGrado = ?";     $params[] = $idGrado; }
        if ($idSeccion   !== null) { $where[] = "ma.idSeccion = ?";   $params[] = $idSeccion; }
        if ($idModalidad !== null) { $where[] = "ma.idModalidad = ?"; $params[] = $idModalidad; }

        $sql = "SELECT ma.idMatricula, ma.idAlumno, ma.idGrado, ma.idSeccion, ma.idModalidad, ma.anio,
                       al.nombre AS alumno, al.nie,
                       g.nombreGrado, s.nombreSeccion, m.nombreModalidad
                  FROM matricula ma
                  JOIN alumnos   al ON al.id_alumno = ma.idAlumno
                  JOIN grado     g  ON g.idGrado = ma.idGrado
                  JOIN seccion   s  ON s.idSeccion = ma.idSeccion
                  JOIN modalidad m  ON m.idModalidad = ma.idModalidad";
        if ($where) $sql .= " WHERE ".implode(" AND ", $where);
        $sql .= " ORDER BY ma.anio DESC, g.idGrado ASC, s.idSeccion ASC, al.nombre ASC";

        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $idMatricula): ?array {
        $st = $this->db->prepare("SELECT * FROM matricula WHERE idMatricula = ?");
        $st->execute([$idMatricula]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /* ==========================================================
     *                 Crear / Actualizar / Eliminar
     * ========================================================== */
    public function crear(int $idAlumno, int $idGrado, int $idSeccion, int $idModalidad, int $anio): bool {
        $sql = "INSERT INTO matricula (idAlumno, idGrado, idSeccion, idModalidad, anio)
                VALUES (?, ?, ?, ?, ?)";
        $st = $this->db->prepare($sql);
        try {
            return $st->execute([$idAlumno, $idGrado, $idSeccion, $idModalidad, $anio]);
        } catch (PDOException $e) {
            // Índice único sugerido: UNIQUE (idAlumno, anio)
            if ($e->getCode() === '23000') {
                throw new Exception("El alumno ya tiene matrícula en el año {$anio}.");
            }
            throw $e;
        }
    }

    public function actualizar(int $idMatricula, int $idAlumno, int $idGrado, int $idSeccion, int $idModalidad, int $anio): bool {
        $sql = "UPDATE matricula
                   SET idAlumno = ?, idGrado = ?, idSeccion = ?, idModalidad = ?, anio = ?
                 WHERE idMatricula = ?";
        $st = $this->db->prepare($sql);
        try {
            return $st->execute([$idAlumno, $idGrado, $idSeccion, $idModalidad, $anio, $idMatricula]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception("El alumno ya tiene matrícula en el año {$anio}.");
            }
            throw $e;
        }
    }

    public function eliminar(int $idMatricula): bool {
        $st = $this->db->prepare("DELETE FROM matricula WHERE idMatricula = ?");
        return $st->execute([$idMatricula]);
    }

    /* ==========================================================
     *           Helpers de validación/consulta adicionales
     * ========================================================== */
    public function existeParaAlumnoYAnio(int $idAlumno, int $anio, ?int $exceptId = null): bool {
        $sql = "SELECT COUNT(*)
                  FROM matricula
                 WHERE idAlumno = ? AND anio = ?";
        $params = [$idAlumno, $anio];
        if ($exceptId !== null) {
            $sql .= " AND idMatricula <> ?";
            $params[] = $exceptId;
        }
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn() > 0;
    }

    /* ==========================================================
     *         Helpers para el Importador HÍBRIDO (CSV)
     * ========================================================== */

    /** Busca alumno por NIE (case-insensitive) */
    public function alumnoPorNIE(string $nie): ?array {
        $st = $this->db->prepare("SELECT * FROM alumnos WHERE UPPER(nie) = UPPER(?) LIMIT 1");
        $st->execute([trim($nie)]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Busca alumno por nombre exacto (case-insensitive) */
    public function alumnoPorNombre(string $nombre): ?array {
        $st = $this->db->prepare("SELECT * FROM alumnos WHERE UPPER(nombre) = UPPER(?) LIMIT 1");
        $st->execute([trim($nombre)]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crea un alumno en la tabla `alumnos`.
     * Campos esperados:
     *  - nombre (req), nie (req y único), responsable, num_responsable,
     *  - modalidad ('Presencial'|'Virtual'), seccion (char), anio (YEAR)
     */
    public function crearAlumno(array $data): int {
        if (empty($data['nombre']) || empty($data['nie'])) {
            throw new Exception("Para crear alumno se requiere nombre y NIE.");
        }

        // Verificar NIE único
        $ex = $this->alumnoPorNIE($data['nie']);
        if ($ex) {
            return (int)$ex['id_alumno']; // ya existe, devolver ID
        }

        // Normalizar modalidad
        $modalidad = $data['modalidad'] ?? 'Presencial';
        $modalidad = (strtolower($modalidad) === 'virtual') ? 'Virtual' : 'Presencial';

        // Sección (char) y año (YEAR)
        $seccion = $data['seccion'] ?? 'A';
        $anio    = (int)($data['anio'] ?? date('Y'));

        $sql = "INSERT INTO alumnos (nombre, nie, responsable, num_responsable, modalidad, seccion, anio, fecha_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $st  = $this->db->prepare($sql);
        $ok  = $st->execute([
            $data['nombre'],
            $data['nie'],
            $data['responsable']     ?? '',
            $data['num_responsable'] ?? '',
            $modalidad,
            $seccion,
            $anio
        ]);
        if (!$ok) {
            throw new Exception("No se pudo crear el alumno: {$data['nombre']}");
        }
        return (int)$this->db->lastInsertId();
    }

    /**
     * A partir de una fila CSV, resuelve ID de alumno:
     * - Si NIE existe: devuelve ese ID.
     * - Si no existe NIE pero hay nombre exacto y existe: devuelve ese ID.
     * - Si no existe ninguno: crea el alumno y devuelve su nuevo ID.
     *
     * Campos aceptados en $fila:
     *  alumnoNombre, nie, responsable, num_responsable, alumnoModalidad, seccion, anio
     */
    public function resolverAlumnoCSV(array $fila): int {
        $nie    = trim((string)($fila['nie']            ?? ''));
        $nombre = trim((string)($fila['alumnoNombre']   ?? ''));

        if ($nie !== '') {
            $ex = $this->alumnoPorNIE($nie);
            if ($ex) return (int)$ex['id_alumno'];
        }

        if ($nie === '' && $nombre !== '') {
            $ex = $this->alumnoPorNombre($nombre);
            if ($ex) return (int)$ex['id_alumno'];
        }

        if ($nombre === '' || $nie === '') {
            throw new Exception("Faltan campos para crear alumno (alumnoNombre y NIE).");
        }

        return $this->crearAlumno([
            'nombre'          => $nombre,
            'nie'             => $nie,
            'responsable'     => $fila['responsable']     ?? '',
            'num_responsable' => $fila['num_responsable'] ?? '',
            'modalidad'       => $fila['alumnoModalidad'] ?? 'Presencial', // enum('Presencial','Virtual')
            'seccion'         => $fila['seccion']         ?? 'A',
            'anio'            => (int)($fila['anio']      ?? date('Y')),
        ]);
    }

    /**
     * Resuelve IDs de catálogos por nombre (map) o toma el ID si viene.
     * $map: array 'NOMBRE_MAYUS' => id (por ejemplo, grados/secciones/modalidades)
     */
    public function resolverCatalogo(array $map, ?string $nombre, ?int $id): int {
        if ($id && $id > 0) return (int)$id;
        $key = strtoupper(trim((string)$nombre));
        if ($key !== '' && isset($map[$key])) return (int)$map[$key];
        throw new Exception("Catálogo no reconocido: ".($nombre ?? 'N/A'));
    }

    /**
     * Upsert de matrícula por (idAlumno, anio):
     *  - Si ya existe → UPDATE
     *  - Si no existe → INSERT
     */
    public function upsertMatricula(int $idAlumno, int $idGrado, int $idSeccion, int $idModalidad, int $anio): void {
        $st = $this->db->prepare("SELECT idMatricula FROM matricula WHERE idAlumno = ? AND anio = ? LIMIT 1");
        $st->execute([$idAlumno, $anio]);
        $idMat = $st->fetchColumn();

        if ($idMat) {
            $this->actualizar((int)$idMat, $idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
        } else {
            $this->crear($idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
        }
    }
}
