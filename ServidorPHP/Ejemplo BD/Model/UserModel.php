<?php
require_once 'User.php';

class UserModel {
    private $conn;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $db;
    }

    public function buscarPorProfile_Code($Profile_code) {
        $query = "SELECT * FROM User_ WHERE Profile_code = :Profile_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Profile_code', $Profile_code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new User($result['profile_Code'], $result['card_no'], $result['gender']);
        } else {
            return null;
        }
    }

    public function borrarPorProfile_Code($Profile_code) {
        $query = "DELETE FROM User_ WHERE Profile_code = :Profile_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Profile_code', $Profile_code);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM User_";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($result as $row) {
            $users[] = new User($row['profile_Code'], $row['card_no'], $row['gender']);
        }
        return $users;
    }
}
?>
