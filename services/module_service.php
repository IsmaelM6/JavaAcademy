<?php
require_once __DIR__ . '/../config/database.php';

class ModuleService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener todos los módulos
    public function getModules() {
        $stmt = $this->pdo->query("SELECT * FROM modulos ORDER BY moduloId ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el progreso de los módulos del usuario
    public function getProgress($userId) {
        $stmt = $this->pdo->prepare("SELECT moduloId, completado FROM progreso WHERE alumnoId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener módulos únicos con su progreso
    public function getUniqueModulesWithProgress($modules, $progress) {
        $modulesUnique = array();
        foreach ($modules as $module) {
            $moduleId = $module['moduloId'];
            if (!isset($modulesUnique[$moduleId])) {
                $modulesUnique[$moduleId] = $module;
                $modulesUnique[$moduleId]['progreso'] = 0;  // Inicializar el progreso
                $modulesUnique[$moduleId]['activo'] = false; // Inicializar el estado activo
            }
        }

        // Actualizar los módulos con el progreso
        foreach ($progress as $p) {
            if (isset($modulesUnique[$p['moduloId']])) {
                $modulesUnique[$p['moduloId']]['progreso'] = $p['completado'];
                if ($p['completado'] >= 75) {
                    $modulesUnique[$p['moduloId']]['activo'] = true;
                }
            }
        }

        // Convertir el array asociativo a un array indexado
        return array_values($modulesUnique);
    }

    // Calcular el progreso general del curso
    public function calculateOverallProgress($modules) {
        $totalProgress = 0;
        $totalModules = count($modules);
        foreach ($modules as $module) {
            $totalProgress += $module['progreso'];
        }

        // Calcular el progreso general como un promedio
        return $totalModules > 0 ? round(($totalProgress / $totalModules), 2) : 0;
    }

    // Actualizar el progreso de un módulo para un usuario
    public function updateProgress($userId, $moduleId, $newProgress) {
        // Verificar si ya existe un registro para este usuario y módulo
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM progreso WHERE alumnoId = ? AND moduloId = ?");
        $stmt->execute([$userId, $moduleId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // Actualizar el progreso existente
            $stmt = $this->pdo->prepare("UPDATE progreso SET completado = ? WHERE alumnoId = ? AND moduloId = ?");
            $stmt->execute([$newProgress, $userId, $moduleId]);
        } else {
            // Insertar un nuevo registro de progreso
            $stmt = $this->pdo->prepare("INSERT INTO progreso (alumnoId, moduloId, completado) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $moduleId, $newProgress]);
        }
    }
}
