<?php
require_once __DIR__ . '/../config/database.php';

class ConexionService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarInicioSesion($alumnoId) {
        $stmt = $this->pdo->prepare("INSERT INTO conexiones (alumnoId, inicioSesion) VALUES (?, NOW())");
        return $stmt->execute([$alumnoId]);
    }

    public function registrarFinSesion($alumnoId) {
        $stmt = $this->pdo->prepare("
            UPDATE conexiones 
            SET finSesion = NOW(), 
                duracion = TIMEDIFF(NOW(), inicioSesion) 
            WHERE alumnoId = ? AND finSesion IS NULL
            ORDER BY inicioSesion DESC 
            LIMIT 1
        ");
        return $stmt->execute([$alumnoId]);
    }

    public function getStudentConnections() {
        $stmt = $this->pdo->prepare("
            SELECT c.alumnoId, c.inicioSesion AS hora_inicio, c.finSesion AS hora_finalizacion, 
                   c.duracion AS duracion_sesion
            FROM conexiones c
            ORDER BY c.inicioSesion DESC
            LIMIT 10
        ");
        $stmt->execute();
        $connections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los nombres de los alumnos en una consulta separada
        $alumnoIds = array_column($connections, 'alumnoId');
        if (!empty($alumnoIds)) {
            $placeholders = implode(',', array_fill(0, count($alumnoIds), '?'));
            
            $stmtAlumnos = $this->pdo->prepare("
                SELECT alumnoId AS id, nombreUsuario AS nombre FROM alumnos WHERE alumnoId IN ($placeholders)
            ");
            $stmtAlumnos->execute($alumnoIds);
            $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_KEY_PAIR);

            // Combinar los resultados
            foreach ($connections as &$connection) {
                $connection['alumno'] = $alumnos[$connection['alumnoId']] ?? 'Desconocido';
                unset($connection['alumnoId']); // Eliminar el ID del alumno del resultado final
            }
        }

        return $connections;
    }
}
