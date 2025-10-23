<?php
class User extends Profile{
    private $card_no;
    private $gender;


    public function __construct($user_code, $user_name, $passwd, $email, $name_, $Surname, $Telephone, $card_no, $gender) {
        $this->user_code = $user_code;
        $this->user_name = $user_name;
        $this->passwd = $passwd;
        $this->email = $email;
        $this->name_ = $name_;
        $this->Surname = $Surname;
        $this->Telephone = $Telephone;
        $this->card_no = $card_no;
        $this->gender = $gender;
    }

    public function getCard_no() { return $this->card_no; }
    public function getGender() { return $this->gender; }



}
?>
