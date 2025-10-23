<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/LibroController.php';

$Profile_code = $_GET['Profile_code'] ?? '';

$controller = new LibroController();
$profile = $controller->buscarProfile($Profile_code);
$user = $controller->buscarUser($Profile_code);

if ($user) {
    echo json_encode([
        'Profile_code' => $user->getProfile_code(),
        'card_no' => $user->getCard_no(),
        'gender' => $user->getGender()
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['error' => 'User no encontrado']);
}
if ($profile) {
    echo json_encode([
        'Profile_code' => $profile->getProfile_code(),
        'username' => $profile->getUsername(),
        'name' => $profile->getName(),
        'surname' => $profile->getSurname(),
        'gmail' => $profile->getGmail(),
        'telephone' => $profile->getTelephone(),
        'password' => $profile->getPassword()
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['error' => 'Profile no encontrado']);
}
