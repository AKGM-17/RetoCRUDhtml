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
        $query = "SELECT * FROM Profile_ WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Profile($result['id'], $result['username'], $result['name'], $result['surname'], $result['gmail'], $result['telephone'], $result['password']);
        } else {
            return null;
        }
    }

    public function borrarPorId($id) {
        $query = "DELETE FROM Profile_ WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    public function getAllProfiles() {
        $query = "SELECT * FROM Profile_";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $profiles = [];
        foreach ($result as $row) {
            $users[] = new Profile($row['id'], $row['username'], $row['name'], $row['surname'], $row['gmail'], $row['telephone'], $row['password']);
        }
        return $users;
    }
}
?>
