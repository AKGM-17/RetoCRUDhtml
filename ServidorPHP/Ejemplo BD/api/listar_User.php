<?php
// Suppress error display to keep JSON clean
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/ModelController.php';

try {
$controller = new ModelController();
    $users = $controller->listarUsers();
    

    

    // Normalize output to plain associative arrays
    $out = [];
    if (is_array($users)) {
        foreach ($users as $user) {
            $code = $user->getId();
            $out[] = [
                'id' => $code,
                'Profile_code' => $code,
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'gmail' => $user->getGmail(),
                'telephone' => $user->getTelephone(),
                'password' => $user->getPassword(),
                'card_no' => $user->getCard_no(),
                'gender' => $user->getGender(),
            ];
        }
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error interno']);
}
