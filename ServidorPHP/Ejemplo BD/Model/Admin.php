<?php
require_once 'Profile.php';

class Admin extends Profile {
    private $profileCode;
    private $current_account;

    public function __construct($id, $username, $name, $surname, $gmail, $telephone, $password, $profileCode, $current_account) {
        parent::__construct($id, $username, $name, $surname, $gmail, $telephone, $password);
        $this->profileCode = $profileCode;
        $this->current_account = $current_account;
    }

    public function getProfileCode() { return $this->profileCode; }
    public function getCurrent_account() { return $this->current_account; }

    public function getUsername() { return parent::getUsername(); }
    public function getName() { return parent::getName(); }
    public function getSurname() { return parent::getSurname(); }
    public function getGmail() { return parent::getGmail(); }
    public function getTelephone() { return parent::getTelephone(); }
    public function getPassword() { return parent::getPassword(); }

    public function mostrar() {
        return "[$this->profileCode] $this->current_account";
    }
}
?>
