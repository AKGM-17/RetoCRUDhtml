<?php
class Database {
    private $host = "localhost";
    private $db_name = "retocrud";
    private $username = "root";
    private $password = "abcd*1234";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            // Don't echo errors here - let them bubble up to be handled by the caller
            throw $e;
        }
        return $this->conn;
    }
}
?>
