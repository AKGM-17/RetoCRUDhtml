<?php
require_once '../config/Database.php';
require_once '../model/UserModel.php';


class CRUDController {
    private $UserModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->UserModel = new UserModel($db);
    }

    public function LogIn($usname, $passwrd) {
        return $this->UserModel->Logger($isLogged);
    }

}
?>


