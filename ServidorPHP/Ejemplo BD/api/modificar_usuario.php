<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\htdocs\RetoCrudHtml\php_errors.log');

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/ModelController.php';

$Profile_code = $_POST['Profile_code'] ?? $_GET['Profile_code'] ?? '';
$username = $_POST['username'] ?? $_GET['username'] ?? '';
$name = $_POST['name'] ?? $_GET['name'] ?? '';
$surname = $_POST['surname'] ?? $_GET['surname'] ?? '';
$gmail = $_POST['gmail'] ?? $_GET['gmail'] ?? '';
$telephone = $_POST['telephone'] ?? $_GET['telephone'] ?? '';
$password = $_POST['password'] ?? $_GET['password'] ?? '';
$card_no = $_POST['card_no'] ?? $_GET['card_no'] ?? '';
$gender = $_POST['gender'] ?? $_GET['gender'] ?? '';


try {
    if ($Profile_code === '') {
        echo json_encode(['error' => 'Profile_code requerido']);
        exit;
    }

    $controller = new ModelController();

    // Buscar el usuario actual para obtener los datos existentes
    $currentProfile = $controller->buscarProfile($Profile_code);
    $currentUser = $controller->buscarUser($Profile_code);
    

    if (!$currentProfile) {
        echo json_encode(['error' => 'Usuario no encontrado', 'debug' => 'Profile_code: ' . $Profile_code]);
        exit;
    }

    // Check if we have any fields to update
    $hasProfileFields = $username !== '' || $name !== '' || $surname !== '' || $gmail !== '' || $telephone !== '' || $password !== '';
    $hasUserFields = $card_no !== '' || $gender !== '';
    

    // Si no hay campos para actualizar, devolver error
    if (!$hasProfileFields && !$hasUserFields) {
        echo json_encode([
            'error' => 'No se proporcionaron campos para actualizar',
            'debug' => [
                'fields_sent' => [
                    'username' => $username,
                    'name' => $name,
                    'surname' => $surname,
                    'gmail' => $gmail,
                    'telephone' => $telephone,
                    'password' => $password,
                    'card_no' => $card_no,
                    'gender' => $gender
                ]
            ]
        ]);
        exit;
    }

    // Actualizar todos los campos que se proporcionaron
    if ($username !== '') $currentProfile->setUsername($username);
    if ($name !== '') $currentProfile->setName($name);
    if ($surname !== '') $currentProfile->setSurname($surname);
    if ($gmail !== '') $currentProfile->setGmail($gmail);
    if ($telephone !== '') $currentProfile->setTelephone($telephone);
    if ($password !== '') $currentProfile->setPassword($password);


    // Si hay datos de User para actualizar
    $userToUpdate = null;
    if ($currentUser) {
        $userToUpdate = $currentUser;
        if ($card_no !== '') $userToUpdate->setCard_no($card_no);
        if ($gender !== '') $userToUpdate->setGender($gender);
    }

    

    // Ejecutar la actualización
    $result = $controller->modificarUsuario($currentProfile, $userToUpdate);

    if (!is_array($result)) {
        echo json_encode(['error' => 'Respuesta inválida del controller']);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'profile_updated' => $result['profile_updated'],
        'user_updated' => $result['user_updated'],
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error interno del servidor']);
}
