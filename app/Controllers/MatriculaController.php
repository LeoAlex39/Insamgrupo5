<?php
class MatriculaController {
    private $model;
    public function __construct() { $this->model = new Matricula(); }

    /* ====== Guards / permisos ====== */
    private function requireDirector() {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['Director','Subdirector','Orientador'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm'); exit;
        }
    }

    /* ====== Listado con filtros ====== */
    public function index() {
        $this->requireDirector();

        $anio        = isset($_GET['anio'])        ? (int)$_GET['anio']        : null;
        $idGrado     = isset($_GET['idGrado'])     ? (int)$_GET['idGrado']     : null;
        $idSeccion   = isset($_GET['idSeccion'])   ? (int)$_GET['idSeccion']   : null;
        $idModalidad = isset($_GET['idModalidad']) ? (int)$_GET['idModalidad'] : null;

        $rows        = $this->model->listar($anio, $idGrado, $idSeccion, $idModalidad);
        $grados      = $this->model->grados();
        $secciones   = $this->model->secciones();
        $modalidades = $this->model->modalidades();

        include VIEW_PATH . '/matricula/index.php';
    }

    /* ====== Crear ====== */
    public function crear() {
        $this->requireDirector();
        $alumnos     = $this->model->alumnosAll();
        $grados      = $this->model->grados();
        $secciones   = $this->model->secciones();
        $modalidades = $this->model->modalidades();
        $error = $_GET['error'] ?? null;

        include VIEW_PATH . '/matricula/crear.php';
    }

    public function store() {
        $this->requireDirector();

        $idAlumno    = (int)$_POST['idAlumno'];
        $idGrado     = (int)$_POST['idGrado'];
        $idSeccion   = (int)$_POST['idSeccion'];
        $idModalidad = (int)$_POST['idModalidad'];
        $anio        = (int)$_POST['anio'];

        try {
            $this->model->crear($idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=index');
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=crear&error=' . $msg);
        }
        exit;
    }

    /* ====== Editar ====== */
    public function editar() {
        $this->requireDirector();

        $id  = (int)($_GET['id'] ?? 0);
        $row = $this->model->find($id);
        if (!$row) { http_response_code(404); echo "Matrícula no encontrada"; return; }

        $alumnos     = $this->model->alumnosAll();
        $grados      = $this->model->grados();
        $secciones   = $this->model->secciones();
        $modalidades = $this->model->modalidades();
        $error = $_GET['error'] ?? null;

        include VIEW_PATH . '/matricula/editar.php';
    }

    public function update() {
        $this->requireDirector();

        $idMatricula = (int)$_POST['idMatricula'];
        $idAlumno    = (int)$_POST['idAlumno'];
        $idGrado     = (int)$_POST['idGrado'];
        $idSeccion   = (int)$_POST['idSeccion'];
        $idModalidad = (int)$_POST['idModalidad'];
        $anio        = (int)$_POST['anio'];

        try {
            $this->model->actualizar($idMatricula, $idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=index');
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=editar&id='.$idMatricula.'&error=' . $msg);
        }
        exit;
    }

    /* ====== Eliminar ====== */
    public function eliminar() {
        $this->requireDirector();

        $id  = (int)($_GET['id'] ?? 0);
        $row = $this->model->find($id);
        if (!$row) { http_response_code(404); echo "Matrícula no encontrada"; return; }

        include VIEW_PATH . '/matricula/eliminar.php';
    }

    public function destroy() {
        $this->requireDirector();
        $id = (int)$_POST['idMatricula'];
        $this->model->eliminar($id);
        header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=index');
        exit;
    }

    /* ================================================================== */
    /* ===================== Importador CSV NORMAL ====================== */
    /* ================================================================== */
    public function importar() {
        $this->requireDirector();
        $errores = $_GET['errores'] ?? null;
        include VIEW_PATH . '/matricula/importar.php';
    }

    public function procesarImport() {
        $this->requireDirector();

        if (empty($_FILES['archivo']['tmp_name'])) {
            $msg = urlencode('No se recibió archivo.');
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importar&errores='.$msg);
            return;
        }

        $simular = isset($_POST['simular']);
        $upsert  = isset($_POST['upsert']);

        // Catálogos
        $alumnos     = $this->model->alumnosAll();
        $grados      = $this->model->grados();
        $secciones   = $this->model->secciones();
        $modalidades = $this->model->modalidades();

        $idxAlumnoPorNIE = [];
        foreach ($alumnos as $a) { if ($a['nie']) $idxAlumnoPorNIE[strtoupper(trim($a['nie']))] = (int)$a['id_alumno']; }
        $idxAlumnoPorNombre = [];
        foreach ($alumnos as $a) { $idxAlumnoPorNombre[strtoupper(trim($a['nombre']))] = (int)$a['id_alumno']; }

        $idxGradoPorNombre = [];
        foreach ($grados as $g) { $idxGradoPorNombre[strtoupper(trim($g['nombreGrado']))] = (int)$g['idGrado']; }
        $idxSeccionPorNombre = [];
        foreach ($secciones as $s) { $idxSeccionPorNombre[strtoupper(trim($s['nombreSeccion']))] = (int)$s['idSeccion']; }
        $idxModalidadPorNombre = [];
        foreach ($modalidades as $m) { $idxModalidadPorNombre[strtoupper(trim($m['nombreModalidad']))] = (int)$m['idModalidad']; }

        // Abrir CSV
        $fh = fopen($_FILES['archivo']['tmp_name'], 'r');
        if (!$fh) {
            $msg = urlencode('No se pudo abrir el archivo.');
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importar&errores='.$msg);
            return;
        }
        $bom = fread($fh, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($fh);

        // Cabecera
        $cab = fgetcsv($fh, 0, ',');
        if (!$cab) { fclose($fh); $msg = urlencode('Archivo vacío o sin cabecera.'); header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importar&errores='.$msg); return; }
        $idx = [];
        foreach ($cab as $i => $h) $idx[strtolower(trim($h))] = $i;

        $tieneAlumnoPorNIE    = isset($idx['nie']);
        $tieneAlumnoPorId     = isset($idx['idalumno']);
        $tieneAlumnoPorNombre = isset($idx['alumnonombre']);
        if (!$tieneAlumnoPorNIE && !$tieneAlumnoPorId && !$tieneAlumnoPorNombre) {
            fclose($fh);
            $msg = urlencode('Debe incluir NIE o idAlumno o alumnoNombre.');
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importar&errores='.$msg);
            return;
        }
        if (!isset($idx['anio'])) {
            fclose($fh);
            $msg = urlencode('Falta la columna "anio".');
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importar&errores='.$msg);
            return;
        }

        $resultados = ['total'=>0,'insertados'=>0,'actualizados'=>0,'errores'=>[]];
        $filasOK = [];

        while (($row = fgetcsv($fh, 0, ',')) !== false) {
            $resultados['total']++;
            $get = function($k) use ($idx,$row){ return isset($idx[$k]) ? trim((string)$row[$idx[$k]]) : null; };

            // Resolver alumno
            $idAlumno = null;
            if ($tieneAlumnoPorId) {
                $idAlumno = (int)$get('idalumno');
                if ($idAlumno <= 0) $idAlumno = null;
            }
            if (!$idAlumno && $tieneAlumnoPorNIE) {
                $nie = strtoupper((string)$get('nie'));
                if ($nie && isset($idxAlumnoPorNIE[$nie])) $idAlumno = $idxAlumnoPorNIE[$nie];
            }
            if (!$idAlumno && $tieneAlumnoPorNombre) {
                $alNom = strtoupper((string)$get('alumnonombre'));
                if ($alNom && isset($idxAlumnoPorNombre[$alNom])) $idAlumno = $idxAlumnoPorNombre[$alNom];
            }
            if (!$idAlumno) { $resultados['errores'][] = "Fila {$resultados['total']}: Alumno no encontrado."; continue; }

            $anio = (int)$get('anio');
            if ($anio < 2000 || $anio > 2100) { $resultados['errores'][] = "Fila {$resultados['total']}: Año inválido."; continue; }

            // Catálogos
            $idGrado = isset($idx['idgrado']) ? (int)$get('idgrado') : null;
            if (!$idGrado && isset($idx['gradonombre'])) {
                $gn = strtoupper((string)$get('gradonombre'));
                $idGrado = $idxGradoPorNombre[$gn] ?? null;
            }
            if (!$idGrado) { $resultados['errores'][] = "Fila {$resultados['total']}: Grado no válido."; continue; }

            $idSeccion = isset($idx['idseccion']) ? (int)$get('idseccion') : null;
            if (!$idSeccion && isset($idx['seccionnombre'])) {
                $sn = strtoupper((string)$get('seccionnombre'));
                $idSeccion = $idxSeccionPorNombre[$sn] ?? null;
            }
            if (!$idSeccion) { $resultados['errores'][] = "Fila {$resultados['total']}: Sección no válida."; continue; }

            $idModalidad = isset($idx['idmodalidad']) ? (int)$get('idmodalidad') : null;
            if (!$idModalidad && isset($idx['modalidadnombre'])) {
                $mn = strtoupper((string)$get('modalidadnombre'));
                $idModalidad = $idxModalidadPorNombre[$mn] ?? null;
            }
            if (!$idModalidad) { $resultados['errores'][] = "Fila {$resultados['total']}: Modalidad no válida."; continue; }

            $filasOK[] = compact('idAlumno','idGrado','idSeccion','idModalidad','anio');

            if (!$simular) {
                // Upsert: si existe (idAlumno, anio) => update; si no => insert
                $st = Database::getInstance()->prepare("SELECT idMatricula FROM matricula WHERE idAlumno=? AND anio=? LIMIT 1");
                $st->execute([$idAlumno, $anio]);
                $idMatricula = $st->fetchColumn();

                if ($idMatricula) {
                    $ok = $this->model->actualizar((int)$idMatricula, $idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
                    if ($ok) $resultados['actualizados']++;
                } else {
                    $ok = $this->model->crear($idAlumno, $idGrado, $idSeccion, $idModalidad, $anio);
                    if ($ok) $resultados['insertados']++;
                }
            }
        }
        fclose($fh);

        include VIEW_PATH . '/matricula/importar_resultado.php';
    }

    /* ================================================================== */
    /* ======================== Importador HÍBRIDO ====================== */
    /* ================================================================== */
    public function importarHibrido() {
        $this->requireDirector();
        $errores = $_GET['errores'] ?? null;
        include VIEW_PATH . '/matricula/importar_hibrido.php';
    }

    public function procesarImportHibrido() {
        $this->requireDirector();

        if (empty($_FILES['archivo']['tmp_name'])) {
            $msg = urlencode('No se recibió archivo.');
            header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importarHibrido&errores='.$msg);
            return;
        }

        $simular = isset($_POST['simular']); // modo previsualización
        $fh = fopen($_FILES['archivo']['tmp_name'], 'r');
        if (!$fh) { $msg = urlencode('No se pudo abrir el archivo.'); header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importarHibrido&errores='.$msg); return; }
        $bom = fread($fh, 3); if ($bom !== "\xEF\xBB\xBF") rewind($fh);

        $cab = fgetcsv($fh, 0, ',');
        if (!$cab) { fclose($fh); $msg = urlencode('Archivo vacío.'); header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importarHibrido&errores='.$msg); return; }
        $idx = []; foreach ($cab as $i => $h) $idx[strtolower(trim($h))] = $i;

        if (!isset($idx['anio'])) { fclose($fh); $msg = urlencode('Falta columna "anio".'); header('Location: ' . BASE_URL . '/index.php?controller=matricula&action=importarHibrido&errores='.$msg); return; }

        // Catálogos (por nombre)
        $gmap = []; foreach ($this->model->grados() as $g)     $gmap[strtoupper($g['nombreGrado'])] = (int)$g['idGrado'];
        $smap = []; foreach ($this->model->secciones() as $s)  $smap[strtoupper($s['nombreSeccion'])] = (int)$s['idSeccion'];
        $mmap = []; foreach ($this->model->modalidades() as $m)$mmap[strtoupper($m['nombreModalidad'])] = (int)$m['idModalidad'];

        $resultados = ['total'=>0,'creados_alumno'=>0,'insertados'=>0,'actualizados'=>0,'errores'=>[]];
        $preview = [];

        while (($row = fgetcsv($fh, 0, ',')) !== false) {
            $resultados['total']++;
            $get = function($k) use ($idx,$row){ return isset($idx[$k]) ? trim((string)$row[$idx[$k]]) : null; };

            try {
                $alFila = [
                    'alumnoNombre'     => $get('alumnonombre'),
                    'nie'              => $get('nie'),
                    'responsable'      => $get('responsable'),
                    'num_responsable'  => $get('num_responsable'),
                    'alumnoModalidad'  => $get('alumnomodalidad'), // Presencial/Virtual (tabla alumnos)
                    'seccion'          => $get('seccion'),         // opcional en alumnos
                    'anio'             => $get('anio'),
                ];
                $anio = (int)$alFila['anio'];
                if ($anio < 2000 || $anio > 2100) throw new Exception("Año inválido.");

                // ¿Existía alumno antes?
                $exAntes = null;
                if (!empty($alFila['nie'])) $exAntes = $this->model->alumnoPorNIE($alFila['nie']);

                // Resolver/crear alumno
                $idAlumno = $this->model->resolverAlumnoCSV($alFila);
                if (!$exAntes && $idAlumno) $resultados['creados_alumno']++;

                // Resolver catálogos de matrícula (prioriza IDs si vienen)
                $idGrado     = $this->model->resolverCatalogo($gmap, $get('gradonombre'),    (int)($get('idgrado') ?? 0));
                $idSeccion   = $this->model->resolverCatalogo($smap, $get('seccionnombre'),  (int)($get('idseccion') ?? 0));
                $idModalidad = $this->model->resolverCatalogo($mmap, $get('modalidadnombre'),(int)($get('idmodalidad') ?? 0));

                $preview[] = compact('idAlumno','idGrado','idSeccion','idModalidad','anio');

                if (!$simular) {
                    // Upsert matrícula por (idAlumno, anio)
                    $st = Database::getInstance()->prepare("SELECT idMatricula FROM matricula WHERE idAlumno=? AND anio=? LIMIT 1");
                    $st->execute([$idAlumno,$anio]);
                    $idMat = $st->fetchColumn();

                    $this->model->upsertMatricula($idAlumno,$idGrado,$idSeccion,$idModalidad,$anio);
                    if ($idMat) $resultados['actualizados']++; else $resultados['insertados']++;
                }
            } catch (Exception $e) {
                $resultados['errores'][] = "Fila {$resultados['total']}: " . $e->getMessage();
            }
        }
        fclose($fh);

        include VIEW_PATH . '/matricula/importar_hibrido_resultado.php';
    }
}
