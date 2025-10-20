<?php
require_once '../config/Database.php';
require_once '../model/LibroModel.php';


class LibroController {
    private $libroModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->libroModel = new LibroModel($db);
    }

    public function LogIn($usname, $passwrd) {
        return $this->libroModel->buscarPorIsbn($isbn);
    }

}
?>


