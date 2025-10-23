<?php
require_once '../config/Database.php';
require_once '../model/UserModel.php';
require_once '../model/ProfileModel.php';


class ModelController {
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


    public function buscarUser($Profile_code) {
        return $this->userModel->buscarPorProfile_Code($Profile_code);
    }

    public function buscarProfile($Profile_code) {
        // The ProfileModel exposes buscarPorId, assuming Profile_code maps to id
        return $this->profileModel->buscarPorId($Profile_code);
    }

    public function listarUsers() {
        return $this->userModel->getAllUsers();
    }

    public function buscarProfilePorUsername($username) {
        return $this->profileModel->buscarPorUsername($username);
    }

    public function borrarProfilePorUsername($username) {
        return $this->profileModel->borrarPorUsername($username);
    }

    public function borrarUsuario($Profile_code = null, $id = null) {
        $userDeleted = false;
        $profileDeleted = false;
        
        if ($Profile_code !== null && $Profile_code !== '') {
            try { $userDeleted = $userDeleted || $this->userModel->borrarPorProfile_Code($Profile_code); } catch (Exception $e) {}
            try { $profileDeleted = $profileDeleted || $this->profileModel->borrarPorId($Profile_code); } catch (Exception $e) {}
        }
        if ($id !== null && $id !== '') {
            try { $userDeleted = $userDeleted || $this->userModel->borrarPorProfile_Code($id); } catch (Exception $e) {}
            try { $profileDeleted = $profileDeleted || $this->profileModel->borrarPorId($id); } catch (Exception $e) {}
        }
        return [
            'user_deleted' => $userDeleted,
            'profile_deleted' => $profileDeleted
        ];
    }
}
?>
