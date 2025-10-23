<?php
    // Suppress error display to keep JSON clean
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    
    header('Content-Type: application/json; charset=utf-8');
    
    require_once '../controller/ModelController.php';
    
    $Profile_code = $_GET['Profile_code'] ?? '';
    
    try {
        if ($Profile_code === '') {
            echo json_encode(['error' => 'Profile_code requerido']);
            exit;
        }
    
        $controller = new ModelController();
        $profile = $controller->buscarProfile($Profile_code);
        $user = $controller->buscarUser($Profile_code);
    
        $response = [
            'profile' => null,
            'user' => null
        ];
    
        if ($profile) {
            $response['profile'] = [
                'id' => $profile->getId(),
                'username' => $profile->getUsername(),
                'name' => $profile->getName(),
                'surname' => $profile->getSurname(),
                'gmail' => $profile->getGmail(),
                'telephone' => $profile->getTelephone(),
                'password' => $profile->getPassword()
            ];
        }
    
        if ($user) {
            $response['user'] = [
                'Profile_code' => $user->getProfileCode(),
                'card_no' => $user->getCard_no(),
                'gender' => $user->getGender()
            ];
        }
    
        if (!$response['profile'] && !$response['user']) {
            echo json_encode(['error' => 'No encontrado']);
            exit;
        }
    
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error interno']);
    }
