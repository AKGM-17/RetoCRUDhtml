<?php
    // API para verificar estado de login del usuario
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    header('Content-Type: application/json; charset=utf-8');

    require_once '../controller/ModelController.php';

    try {
        // Obtener datos del usuario desde localStorage (esto se hará en el frontend)
        // Por ahora, esta API puede usarse para verificar si un usuario existe
        $username = $_GET['username'] ?? '';

        if ($username === '') {
            echo json_encode([
                'success' => false,
                'error' => 'Username requerido'
            ]);
            exit;
        }

        $controller = new ModelController();
        $profile = $controller->buscarProfilePorUsername($username);

        if ($profile) {
            echo json_encode([
                'success' => true,
                'profile' => [
                    'id' => $profile->getId(),
                    'username' => $profile->getUsername(),
                    'name' => $profile->getName(),
                    'surname' => $profile->getSurname(),
                    'gmail' => $profile->getGmail(),
                    'telephone' => $profile->getTelephone()
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Usuario no encontrado'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error interno del servidor'
        ]);
    }
?>
