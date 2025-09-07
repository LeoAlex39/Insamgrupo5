<?php
class Docente {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** --------- DOCENTE (usuario.rol='Docente') --------- */
    public function listar(): array {
        $st = $this->db->prepare("SELECT idUsuario, nombreUsuario, correo, rol FROM usuario WHERE rol='Docente' ORDER BY nombreUsuario");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $id): ?array {
        $st = $this->db->prepare("SELECT idUsuario, nombreUsuario, correo, rol FROM usuario WHERE idUsuario=? AND rol='Docente'");
        $st->execute([$id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function crear(string $nombre, string $correo, string $password): int {
        // email único
        $chk = $this->db->prepare("SELECT 1 FROM usuario WHERE correo=?");
        $chk->execute([$correo]);
        if ($chk->fetch()) throw new Exception("El correo ya está en uso.");

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $st = $this->db->prepare("INSERT INTO usuario (nombreUsuario, correo, rol, contraseña) VALUES (?,?, 'Docente', ?)");
        $st->execute([$nombre, $correo, $hash]);
        return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, string $nombre, string $correo, ?string $password = null): bool {
        // email único (excluyendo el propio)
        $chk = $this->db->prepare("SELECT 1 FROM usuario WHERE correo=? AND idUsuario<>?");
        $chk->execute([$correo, $id]);
        if ($chk->fetch()) throw new Exception("El correo ya está en uso por otro usuario.");

        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $st = $this->db->prepare("UPDATE usuario SET nombreUsuario=?, correo=?, contraseña=? WHERE idUsuario=? AND rol='Docente'");
            return $st->execute([$nombre, $correo, $hash, $id]);
        } else {
            $st = $this->db->prepare("UPDATE usuario SET nombreUsuario=?, correo=? WHERE idUsuario=? AND rol='Docente'");
            return $st->execute([$nombre, $correo, $id]);
        }
    }

    public function eliminar(int $id): bool {
        // Nota: si hay FKs desde horario, primero tendrías que limpiar sus horarios/asignaciones.
        $st = $this->db->prepare("DELETE FROM usuario WHERE idUsuario=? AND rol='Docente'");
        return $st->execute([$id]);
    }

    /** --------- ASIGNATURAS --------- */
    public function todasAsignaturas(): array {
        $st = $this->db->query("SELECT idAsignatura, nombreAsignatura FROM asignatura ORDER BY nombreAsignatura");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** ids de asignaturas actualmente asignadas al docente */
    public function asignaturasDeDocente(int $idUsuario): array {
        $st = $this->db->prepare("SELECT idAsignatura FROM docenteasignatura WHERE idUsuario=?");
        $st->execute([$idUsuario]);
        return array_map('intval', array_column($st->fetchAll(PDO::FETCH_ASSOC), 'idAsignatura'));
    }

    /** Reemplaza las asignaturas del docente por las provistas en $idsAsignaturas */
    public function setAsignaturas(int $idUsuario, array $idsAsignaturas): void {
        $this->db->beginTransaction();
        try {
            // Borrar actuales
            $del = $this->db->prepare("DELETE FROM docenteasignatura WHERE idUsuario=?");
            $del->execute([$idUsuario]);

            // Insertar nuevas
            if (!empty($idsAsignaturas)) {
                $ins = $this->db->prepare("INSERT INTO docenteasignatura (idUsuario, idAsignatura) VALUES (?,?)");
                foreach ($idsAsignaturas as $ida) {
                    if ($ida === '' || $ida === null) continue;
                    $ins->execute([$idUsuario, (int)$ida]);
                }
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
