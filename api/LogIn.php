<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once '../controller/LibroController.php';

$isbn = $_GET['isbn'] ?? '';

$controller = new LibroController();
$libro = $controller->buscar($isbn);

if ($libro) {
    echo json_encode([
        'isbn' => $libro->getIsbn(),
        'nombre' => $libro->getNombre(),
        'autor' => $libro->getAutor()
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['error' => 'Libro no encontrado']);
}
?>
