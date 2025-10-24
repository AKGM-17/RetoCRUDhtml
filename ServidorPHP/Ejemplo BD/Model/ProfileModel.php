<?php
require_once 'Profile.php';

class ProfileModel {
    private $conn;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $db;
    }

    public function buscarPorId($id) {
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

    public function borrarPorId($id) {
        $query = "DELETE FROM Profile_ WHERE user_code = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    public function buscarPorUsername($username) {
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

    public function getAllProfiles() {
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

    public function actualizarProfile($profile) {
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
}
?>
