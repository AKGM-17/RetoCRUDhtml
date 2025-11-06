<?php
     // Configuración agresiva de logging
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    
    


    header('Content-Type: application/json; charset=utf-8');

    require_once '../controller/ModelController.php';

    $username = $_POST['username'] ?? $_GET['username'] ?? '';
    $password = $_POST['password'] ?? $_GET['password'] ?? '';

    // Debug logging
    error_log("Login attempt - Username: '$username', Password length: " . strlen($password));

    try {
        if ($username === '' || $password === '') {
            error_log("Login failed - Missing credentials");
            echo json_encode([
                'success' => false,
                'error' => 'Username y password son requeridos'
            ]);
            exit;
        }

        $controller = new ModelController();
        $result = $controller->login($username, $password);

        error_log("Login result: " . ($result['success'] ? 'SUCCESS' : 'FAILED - ' . $result['error']));

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log("Login exception: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => 'Error interno del servidor'
        ]);
    }
?>
