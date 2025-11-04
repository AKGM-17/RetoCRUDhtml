<?php
require_once '../config/Database.php';
require_once '../model/UserModel.php';
require_once '../model/ProfileModel.php';
require_once '../model/AdminModel.php';


class ModelController {
    private $userModel;
    private $profileModel;
    private $adminModel;

    public function __construct() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            $this->userModel = new UserModel($db);
            $this->profileModel = new ProfileModel($db);
            $this->adminModel = new AdminModel($db);
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

    public function buscarAdmin($Profile_code) {
        return $this->adminModel->buscarPorProfileCode($Profile_code);
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

    public function modificarUsuario($profile, $user) {
        $profileUpdated = false;
        $userUpdated = false;

        if ($profile !== null) {
            try {
                $profileUpdated = $this->profileModel->actualizarProfile($profile);
            } catch (Exception $e) {
                // Profile update failed
            }
        }

        if ($user !== null) {
            try {
                $userUpdated = $this->userModel->actualizarUser($user);
            } catch (Exception $e) {
                // User update failed
            }
        }

        return [
            'profile_updated' => $profileUpdated,
            'user_updated' => $userUpdated,
        ];
    }
    // En ModelController.php
public function registrarUsuario($username, $name, $surname, $email, $telephone, $password, $card_no, $gender) {
    try {
        // 1. Verificar si el username ya existe
        $existingProfile = $this->profileModel->checkUsername($username);
        if ($existingProfile) {
            return ['success' => false, 'error' => 'El nombre de usuario ya existe'];
        }

        // 2. Verificar si el email ya existe
        $existingEmail = $this->profileModel->checkEmail($email);
        if ($existingEmail) {
            return ['success' => false, 'error' => 'El email ya está registrado'];
        }

        // 3. Insertar en la tabla Profile_
        $profileData = [
            'user_name' => $username,
            'name_' => $name,
            'Surname' => $surname,
            'email' => $email,
            'Telephone' => $telephone,
            'passwd' => $password // Considera hashear la contraseña
        ];

        $profileId = $this->profileModel->insertar($profileData);
        
        if (!$profileId) {
            return ['success' => false, 'error' => 'Error al crear el perfil'];
        }

        // 4. Insertar en la tabla User_
        $userData = [
            'Profile_code' => $profileId,
            'card_no' => $card_no,
            'gender' => $gender
        ];

        $userInserted = $this->userModel->insertar($userData);
        
        if (!$userInserted) {
            // Si falla insertar en User_, eliminar el profile creado
            $this->profileModel->eliminar($profileId);
            return ['success' => false, 'error' => 'Error al crear el usuario'];
        }

        return [
            'success' => true,
            'user_id' => $profileId,
            'message' => 'Usuario registrado exitosamente'
        ];

    } catch (Exception $e) {
        error_log("Error en registrarUsuario: " . $e->getMessage());
        return ['success' => false, 'error' => 'Error interno del servidor'];
    }
}

    public function login($username, $password) {
    try {
        error_log("=== MODELCONTROLLER LOGIN ===");
        
        // Primero autenticar con ProfileModel
        $profile = $this->profileModel->authenticate($username, $password);
       
        error_log("Profile result: " . ($profile ? "SUCCESS - Profile ID: " . $profile->getId() : "NULL"));
        
        if ($profile) {
            // Si la autenticación es exitosa, buscar también en User_ si existe
            $user = null;
            $admin = null;
            try {
                $userResult = $this->userModel->buscarPorProfile_Code($profile->getId());
                error_log("User result: " . ($userResult ? "FOUND" : "NOT FOUND"));
                if ($userResult !== null) {
                    $user = $userResult;
                } else {
                    $adminResult = $this->adminModel->buscarPorProfileCode($profile->getId());
                    if($adminResult !== null) {
                        $admin = $adminResult;
                    }
                    
                }
            } catch (Exception $e) {
                error_log("User search error: " . $e->getMessage());
            }

            error_log("=== RETURNING SUCCESS ===");
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'profile' => [
                    'id' => $profile->getId(),
                    'username' => $profile->getUsername(),
                    'name' => $profile->getName(),
                    'surname' => $profile->getSurname(),
                    'gmail' => $profile->getGmail(),
                    'telephone' => $profile->getTelephone(),
                    'passwd' => $profile->getPassword()
                ],
                'user' => $user ? [
                    'profile_code' => $user->getProfileCode(),
                    'card_no' => $user->getCard_no(),
                    'gender' => $user->getGender()
                ] : null,
                'is_admin' => $this->esAdmin($profile->getUsername())
    
            ];
        }

        error_log("=== RETURNING FAILURE ===");
        return [
            'success' => false,
            'error' => 'Usuario o contraseña incorrectos'
        ];
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Error interno del servidor'
        ];
    }
}

    public function esAdmin($username) {
        try {
            return $this->adminModel->esAdmin($username);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
