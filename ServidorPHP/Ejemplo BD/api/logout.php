<?php
    // API para logout - limpia la sesión y retorna confirmación
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    header('Content-Type: application/json; charset=utf-8');

    try {
        // Limpiar cualquier dato de sesión si existe
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();

        // Limpiar datos del usuario del localStorage (esto se hará en el frontend)
        echo json_encode([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error en logout'
        ]);
    }
?>
