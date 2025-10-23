<?php
// Suppress all error output to prevent breaking JSON response
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/LibroController.php';

$isbn = $_GET['isbn'] ?? '';

if (!$isbn) {
    echo json_encode(['error' => 'ISBN es requerido']);
    exit;
}

try {
    $controller = new LibroController();
    $resultado = $controller->borrar($isbn);

    if ($resultado) {
        echo json_encode(['mensaje' => 'Libro borrado exitosamente']);
    } else {
        echo json_encode(['error' => 'Libro no encontrado o no se pudo borrar']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error interno del servidor']);
}
?>
