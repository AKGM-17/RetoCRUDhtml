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

    public function borrar($isbn) {
        // Not implemented in provided models
        return false;
    }

    public function buscarUser($Profile_code) {
        return $this->userModel->buscarPorProfile_Code($Profile_code);
    }

    public function buscarProfile($Profile_code) {
        // The ProfileModel exposes buscarPorId, assuming Profile_code maps to id
        return $this->profileModel->buscarPorId($Profile_code);
    }

    public function listarProfiles() {
        return $this->profileModel->getAllProfiles();
    }
}
?>
