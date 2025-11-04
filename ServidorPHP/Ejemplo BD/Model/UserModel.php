<?php
require_once 'User.php';

class UserModel
{
    private $conn;

    public function __construct($db)
    {
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $db;
    }

    public function buscarPorProfile_Code($Profile_code)
    {
        $query = "
            SELECT
                p.user_code AS profile_code,
                p.user_code AS pid,
                p.user_name AS username,
                p.name_ AS name,
                p.Surname AS surname,
                p.email AS email,
                p.Telephone AS telephone,
                p.passwd AS password,
                u.card_no AS card_no,
                u.gender AS gender
            FROM User_ u
            JOIN Profile_ p ON p.user_code = u.Profile_code
            WHERE u.Profile_code = :Profile_code
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Profile_code', $Profile_code);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $pid = $row['pid'] ?? null;
            $username = $row['username'] ?? null;
            $name = $row['name'] ?? null;
            $surname = $row['surname'] ?? null;
            $email = $row['email'] ?? null;
            $telephone = $row['telephone'] ?? null;
            $password = $row['password'] ?? null;
            $profileCode = $row['profile_code'] ?? $Profile_code;
            $card_no = $row['card_no'] ?? null;
            $gender = $row['gender'] ?? null;
            return new User($pid, $username, $name, $surname, $email, $telephone, $password, $profileCode, $card_no, $gender);
        }
        return null;
    }

    public function borrarPorProfile_Code($Profile_code)
    {
        $query = "DELETE FROM User_ WHERE Profile_code = :Profile_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Profile_code', $Profile_code);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Return true if at least one row was deleted
        }
        return false;
    }

    public function getAllUsers()
    {
        $query = "
            SELECT 
                p.user_code AS profile_code,
                p.user_code AS pid,
                p.user_name AS username,
                p.name_ AS name,
                p.Surname AS surname,
                p.email AS email,
                p.Telephone AS telephone,
                p.passwd AS password,
                u.card_no AS card_no,
                u.gender AS gender
            FROM User_ u
            JOIN Profile_ p ON p.user_code = u.Profile_code
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $pid = $row['pid'] ?? null;
            $username = $row['username'] ?? null;
            $name = $row['name'] ?? null;
            $surname = $row['surname'] ?? null;
            $email = $row['email'] ?? null;
            $telephone = $row['telephone'] ?? null;
            $password = $row['password'] ?? null;
            $profileCode = $row['profile_code'] ?? null;
            $card_no = $row['card_no'] ?? null;
            $gender = $row['gender'] ?? null;
            $users[] = new User($pid, $username, $name, $surname, $email, $telephone, $password, $profileCode, $card_no, $gender);
        }
        return $users;
    }

    public function actualizarUser($user)
    {
        $query = "UPDATE User_ SET card_no = :card_no, gender = :gender WHERE Profile_code = :profile_code";
        $stmt = $this->conn->prepare($query);

        // Crear variables temporales para evitar errores de referencia
        $profileCode = $user->getProfileCode();
        $cardNo = $user->getCard_no();
        $gender = $user->getGender();

        $stmt->bindParam(':profile_code', $profileCode);
        $stmt->bindParam(':card_no', $cardNo);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    public function insertar($data)
    {
        $query = "INSERT INTO User_ (Profile_code, card_no, gender) 
              VALUES (:Profile_code, :card_no, :gender)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Profile_code', $data['Profile_code']);
        $stmt->bindParam(':card_no', $data['card_no']);
        $stmt->bindParam(':gender', $data['gender']);

        return $stmt->execute();
    }
}
?>