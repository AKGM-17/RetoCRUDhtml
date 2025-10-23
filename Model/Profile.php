<?php
class Profile {
    private $user_code;
    private $user_name;
    private $passwd;
    private $email;
    private $name_;
    private $Surname;
    private $Telephone;


    public function __construct($user_code, $user_name, $passwd, $email, $name_, $Surname, $Telephone) {
    $this->user_code = $user_code;
    $this->user_name = $user_name;
    $this->passwd = $passwd;
    $this->email = $email;
    $this->name_ = $name_;
    $this->Surname = $Surname;
    $this->Telephone = $Telephone;
    }

    public function getUserCode() { return $this->user_code; }
    public function getUserName() { return $this->user_name; }
    public function getPasswd() { return $this->passwd; }
    public function getEmail() { return $this->email; }
    public function getName_() { return $this->name_; }
    public function getSurname() { return $this->Surname; }
    public function getTelefone() { return $this->Telefone; }

   
}
?>
