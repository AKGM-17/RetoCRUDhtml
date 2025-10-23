<?php
// Suppress error display to keep JSON clean
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/LibroController.php';

try {
    $controller = new LibroController();
    $users = $controller->listarUsers();
    

    

    // Normalize output to plain associative arrays
    $out = [];
    if (is_array($users)) {
        foreach ($users as $p) {
            $code = $p->getId();
            $out[] = [
                'id' => $code,
                'Profile_code' => $code,
                'username' => $p->getUsername(),
                'name' => $p->getName(),
                'surname' => $p->getSurname(),
                'gmail' => $p->getGmail(),
                'telephone' => $p->getTelephone(),
                'password' => $p->getPassword(),
                'card_no' => $p->getCard_no(),
                'gender' => $p->getGender(),
            ];
        }
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error interno']);
}
