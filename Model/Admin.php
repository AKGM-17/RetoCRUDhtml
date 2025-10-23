<?php
class User {
    private $Current_account;

    public function __construct($isbn, $Current_account) {
        $this->user_code = $user_code;
        $this->user_name = $user_name;
        $this->passwd = $passwd;
        $this->email = $email;
        $this->name_ = $name_;
        $this->Surname = $Surname;
        $this->Telephone = $Telephone;
        $this->Current_account = $Current_account;
        
    }
    public function getCurrent_account() { return $this->Current_account; }


    
}
?>
