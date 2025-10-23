<?php
require_once 'User.php';

class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function buscarPorIsbn($isbn) {
        $query = "SELECT * FROM libros WHERE isbn = :isbn";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Libro($row['isbn'], $row['nombre'], $row['autor']);
        } else {
            return null;
        }
    }
    public function borrarPorIsbn($isbn) {
        $query = "DELETE FROM libros WHERE isbn = :isbn;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            return  $isbn ;
        } else {
            return null;
        }
    }
}
?>