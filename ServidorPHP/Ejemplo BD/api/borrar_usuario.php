<?php
// Suppress error display to keep JSON clean
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/ModelController.php';

$Profile_code = $_GET['Profile_code'] ?? '';
$id = $_GET['id'] ?? '';
$username = $_GET['username'] ?? '';

try {
    $controller = new ModelController();

    // If neither id nor Profile_code provided, try resolve by username
    if ($Profile_code === '' && $id === '') {
        if ($username === '') {
            echo json_encode(['error' => 'Profile_code, id o username requerido']);
            exit;
        }
        $profile = $controller->buscarProfilePorUsername($username);
        if (!$profile) {
            echo json_encode(['error' => 'Usuario no encontrado por username']);
            exit;
        }
        $resolved = $profile->getId();
        $Profile_code = $resolved;
        $id = $id ?: $resolved;
    }

    $result = $controller->borrarUsuario($Profile_code, $id);

    if (!is_array($result)) {
        echo json_encode(['error' => 'Respuesta inválida']);
        exit;
    }

    if (!$result['user_deleted'] && !$result['profile_deleted']) {
        echo json_encode(['error' => 'No se borró ningún registro']);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'user_deleted' => $result['user_deleted'],
        'profile_deleted' => $result['profile_deleted']
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error interno']);
}
