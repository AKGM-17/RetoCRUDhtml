<?php
require_once 'Profile.php';

class User extends Profile {
    private $profileCode;
    private $card_no;
    private $gender;

    public function __construct($id, $username, $name, $surname, $gmail, $telephone, $password, $profileCode, $card_no, $gender) {
        parent::__construct($id, $username, $name, $surname, $gmail, $telephone, $password);
        $this->profileCode = $profileCode;
        $this->card_no = $card_no;
        $this->gender = $gender;
    }

    public function getProfileCode() { return $this->profileCode; }
    public function getCard_no() { return $this->card_no; }
    public function getGender() { return $this->gender; }

    public function getUsername() { return parent::getUsername(); }
    public function getName() { return parent::getName(); }
    public function getSurname() { return parent::getSurname(); }
    public function getGmail() { return parent::getGmail(); }
    public function getTelephone() { return parent::getTelephone(); }
    public function getPassword() { return parent::getPassword(); }

    public function mostrar() {
        return "[$this->profileCode] $this->card_no - $this->gender";
    }
}
?>
