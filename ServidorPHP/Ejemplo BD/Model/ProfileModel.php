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
            $pid = $result['Profile_code'] ?? $result['profile_Code'] ?? $result['profile_code'] ?? ($result['id'] ?? null);
            $username = $result['user_name'] ?? $result['Username'] ?? null;
            $name = $result['name'] ?? $result['Name'] ?? null;
            $surname = $result['surname'] ?? $result['Surname'] ?? null;
            $gmail = $result['email'] ?? $result['Email'] ?? null;
            $telephone = $result['telephone'] ?? $result['Telephone'] ?? null;
            $password = $result['passwd'] ?? $result['Password'] ?? null;
            return new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
        } else {
            return null;
        }
    }

    public function borrarPorId($id) {
        $query = "DELETE FROM Profile_ WHERE Profile_code = :id OR id = :id";
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
            $pid = $result['Profile_code'] ?? $result['profile_Code'] ?? $result['profile_code'] ?? ($result['id'] ?? null);
            $username = $result['user_name'] ?? $result['Username'] ?? null;
            $name = $result['name_'] ?? $result['Name'] ?? null;
            $surname = $result['surname'] ?? $result['Surname'] ?? null;
            $gmail = $result['email'] ?? $result['Email'] ?? null;
            $telephone = $result['telephone'] ?? $result['Telephone'] ?? null;
            $password = $result['passwd'] ?? $result['Password'] ?? null;
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
            $pid = $row['Profile_code'] ?? $row['profile_Code'] ?? $row['profile_code'] ?? ($row['id'] ?? null);
            $username = $row['user_name'] ?? $row['Username'] ?? null;
            $name = $row['name_'] ?? $row['name'] ?? null;
            $surname = $row['Surname'] ?? $row['Surname'] ?? null;
            $gmail = $row['email'] ?? $row['Email'] ?? null;
            $telephone = $row['Telephone'] ?? $row['Telephone'] ?? null;
            $password = $row['passwd'] ?? $row['Password'] ?? null;
            $profiles[] = new Profile($pid, $username, $name, $surname, $gmail, $telephone, $password);
        }
        return $profiles;
    }
}
?>
