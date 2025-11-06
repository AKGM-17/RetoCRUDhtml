<?php
require_once '../config/Database.php';
require_once '../model/UserModel.php';
require_once '../model/ProfileModel.php';


class LibroController {
    private $userModel;
    private $profileModel;

    public function __construct() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            $this->userModel = new UserModel($db);
            $this->profileModel = new ProfileModel($db);
        } catch (Exception $e) {
            throw $e; // Re-throw to be caught by the API layer
        }
    }

}
?>
