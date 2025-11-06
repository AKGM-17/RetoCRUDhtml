<?php
require_once 'Profile.php';

class ProfileModel
{
    private $conn;

    public function __construct($db)
    {
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $db;
    }

    public function buscarPorId($id)
    {
        // Use user_code as identifier, the primary key in Profile_
        $query = "SELECT * FROM Profile_ WHERE user_code = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $pid = $result['user_code']; // El campo de clave primaria es user_code
            $username = $result['user_name'] ?? null;
            $name = $result['name_'] ?? null;
            $surname = $result['Surname'] ?? null;
            $gmail = $result['email'] ?? null;
            $telephone = $result['Telephone'] ?? null;
            $password = $result['passwd'] ?? null;
            return new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
        } else {
            return null;
        }
    }

    public function borrarPorId($id)
    {
        $query = "DELETE FROM Profile_ WHERE user_code = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    public function buscarPorUsername($username)
    {
        $query = "SELECT * FROM Profile_ WHERE user_name = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $pid = $result['user_code']; // El campo de clave primaria es user_code
            $username = $result['user_name'] ?? null;
            $name = $result['name_'] ?? null;
            $surname = $result['Surname'] ?? null;
            $gmail = $result['email'] ?? null;
            $telephone = $result['Telephone'] ?? null;
            $password = $result['passwd'] ?? null;
            return new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
        }
        return null;
    }
    public function checkUsername($username)
    {
        $query = "SELECT * FROM Profile_ WHERE user_name = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkEmail($email)
    {
        $query = "SELECT * FROM Profile_ WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProfiles()
    {
        $query = "SELECT * FROM Profile_";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $profiles = [];
        foreach ($result as $row) {
            $pid = $row['user_code']; // El campo de clave primaria es user_code
            $username = $row['user_name'] ?? null;
            $name = $row['name_'] ?? null;
            $surname = $row['Surname'] ?? null;
            $gmail = $row['email'] ?? null;
            $telephone = $row['Telephone'] ?? null;
            $password = $row['passwd'] ?? null;
            $profiles[] = new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
        }
        return $profiles;
    }

    public function borrarPorUsername($username)
    {
        $query = "DELETE FROM Profile_ WHERE user_name = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    // En el método authenticate de ProfileModel.php, modifica esta parte:
    public function authenticate($username, $password)
    {
        error_log("=== AUTHENTICATION DEBUG ===");
        error_log("Input username: '$username'");

        $query = "SELECT * FROM Profile_ WHERE user_name = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            error_log("USER FOUND IN DATABASE");
            $storedPassword = $result['passwd'] ?? '';
            $inputPassword = $password;

            error_log("Password match: " . ($storedPassword === $inputPassword ? 'YES' : 'NO'));

            if ($storedPassword === $inputPassword) {
                error_log("=== CREATING PROFILE OBJECT ===");
                $pid = $result['user_code'];
                $username = $result['user_name'] ?? null;
                $name = $result['name_'] ?? null;
                $surname = $result['Surname'] ?? null;
                $gmail = $result['email'] ?? null;
                $telephone = $result['Telephone'] ?? null;
                $password = $result['passwd'] ?? null;

                error_log("Profile data - ID: $pid, Username: $username");

                $profile = new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
                error_log("Profile object created: " . ($profile ? "YES" : "NO"));
                error_log("Profile ID: " . $profile->getId());

                return $profile;
            } else {
                error_log("=== PASSWORD MISMATCH ===");
            }
        } else {
            error_log("=== USER NOT FOUND ===");
        }

        error_log("=== RETURNING NULL ===");
        return null;
    }

    public function actualizarProfile($profile)
    {
        $query = "UPDATE Profile_ SET user_name = :username, passwd = :password, email = :email, name_ = :name, Surname = :surname, Telephone = :telephone WHERE user_code = :id";
        $stmt = $this->conn->prepare($query);

        // Crear variables temporales para evitar errores de referencia
        $id = $profile->getId();
        $username = $profile->getUsername();
        $password = $profile->getPassword();
        $email = $profile->getGmail();
        $name = $profile->getName();
        $surname = $profile->getSurname();
        $telephone = $profile->getTelephone();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':telephone', $telephone);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    public function insertar($data)
    {
        $query = "INSERT INTO Profile_ (user_name, name_, Surname, email, Telephone, passwd) 
              VALUES (:user_name, :name_, :Surname, :email, :Telephone, :passwd)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $data['user_name']);
        $stmt->bindParam(':name_', $data['name_']);
        $stmt->bindParam(':Surname', $data['Surname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':Telephone', $data['Telephone']);
        $stmt->bindParam(':passwd', $data['passwd']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function eliminar($id)
    {
        $query = "DELETE FROM Profile_ WHERE user_code = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>