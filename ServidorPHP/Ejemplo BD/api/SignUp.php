<?php
// registrar_usuario.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../controller/ModelController.php';

// Leer el input JSON
$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$name = $input['name'] ?? '';
$surname = $input['surname'] ?? '';
$email = $input['gmail'] ?? '';
$telephone = $input['telephone'] ?? '';
$password = $input['password'] ?? '';
$card_no = $input['card_no'] ?? '';
$gender = $input['gender'] ?? '';

try {
    if (empty($username) || empty($name) || empty($surname) || empty($email) || empty($password) || empty($card_no) || empty($gender)) {
        echo json_encode([
            'success' => false,
            'error' => 'Todos los campos obligatorios deben ser completados'
        ]);
        exit;
    }

    $controller = new ModelController();
    $result = $controller->registrarUsuario($username, $name, $surname, $email, $telephone, $password, $card_no, $gender);

    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'user_id' => $result['user_id'] ?? null
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $result['error'] ?? 'Error al registrar el usuario'
        ]);
    }
} catch (Exception $e) {
    error_log("Error en registrar_usuario.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor'
    ]);
}
?>