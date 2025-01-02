<?php
require_once __DIR__ . '/../config/database.php';

class ProgressStudentService{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getProgressStudent(){
        $stmt = $this->pdo->prepare("
            SELECT a.alumnoId, a.nombreUsuario, p.moduloId, m.titulo, p.completado 
            FROM alumnos a 
            INNER JOIN progreso p ON a.alumnoId = p.alumnoId
            INNER JOIN modulos m ON m.moduloId = p.moduloId
            ORDER BY a.alumnoId
            ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProgressStudentByName($name){
        $stmnt = $this->pdo->prepare("
            SELECT a.alumnoId, a.nombreUsuario, p.moduloId, m.titulo, p.completado
            FROM alumnos a
            INNER JOIN progreso p ON a.alumnoId = p.alumnoId
            INNER JOIN modulos m ON m.moduloId = p.moduloId
            WHERE a.nombreUsuario LIKE :name;
        ");
        $stmnt->execute([':name' => "%$name%"]);
        return $stmnt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>