<?php
class Profile {
    private $id;
    private $username;
    private $name;
    private $surname;
    private $gmail;
    private $telephone;
    private $password;

    public function __construct($id, $username, $name, $surname, $gmail, $telephone, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->surname = $surname;
        $this->gmail = $gmail;
        $this->telephone = $telephone;
        $this->password = $password;
    }

    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getName() { return $this->name; }
    public function getSurname() { return $this->surname; }
    public function getGmail() { return $this->gmail; }
    public function getTelephone() { return $this->telephone; }
    public function getPassword() { return $this->password; }
    public function setUsername($username) { $this->username = $username; }
    public function setName($name) { $this->name = $name; }
    public function setSurname($surname) { $this->surname = $surname; }
    public function setGmail($gmail) { $this->gmail = $gmail; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setPassword($password) { $this->password = $password; }

    public function mostrar() {
        return "[$this->id] $this->username - $this->name - $this->surname - $this->gmail - $this->telephone - $this->password";
    }
}
?>
