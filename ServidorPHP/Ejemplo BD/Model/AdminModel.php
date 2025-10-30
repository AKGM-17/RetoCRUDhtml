<?php
require_once 'Admin.php';

class AdminModel {
    private $conn;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $db;
    }

    public function buscarPorProfileCode($profile_code) {
        $query = "
            SELECT
                a.Profile_code AS profile_code,
                a.Current_account AS current_account,
                p.user_name AS username,
                p.name_ AS name,
                p.Surname AS surname,
                p.email AS gmail,
                p.Telephone AS telephone,
                p.passwd AS password
            FROM Admin_ a
            JOIN Profile_ p ON p.user_code = a.Profile_code
            WHERE a.Profile_code = :profile_code
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':profile_code', $profile_code);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Admin(
                $row['profile_code'],
                $row['username'],
                $row['name'],
                $row['surname'],
                $row['gmail'],
                $row['telephone'],
                $row['password'],
                $row['profile_code'],
                $row['current_account']
            );
        }
        return null;
    }

    public function esAdmin($username) {
        // Primero buscar el profile por username
        $query = "SELECT user_code FROM Profile_ WHERE user_name = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $profile_code = $result['user_code'];
            // Verificar si existe en la tabla Admin_
            $query2 = "SELECT COUNT(*) as count FROM Admin_ WHERE Profile_code = :profile_code";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':profile_code', $profile_code);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            return $result2['count'] > 0;
        }
        return false;
    }

    public function getAllAdmins() {
        $query = "
            SELECT
                a.Profile_code AS profile_code,
                a.Current_account AS current_account,
                p.user_name AS username,
                p.name_ AS name,
                p.Surname AS surname,
                p.email AS gmail,
                p.Telephone AS telephone,
                p.passwd AS password
            FROM Admin_ a
            JOIN Profile_ p ON p.user_code = a.Profile_code
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $admins = [];
        foreach ($rows as $row) {
            $admins[] = new Admin(
                $row['profile_code'],
                $row['username'],
                $row['name'],
                $row['surname'],
                $row['gmail'],
                $row['telephone'],
                $row['password'],
                $row['profile_code'],
                $row['current_account']
            );
        }
        return $admins;
    }
}
?>
