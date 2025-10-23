<?php
class User {
    private $profileCode;
    private $card_no;
    private $gender;


    public function __construct($profileCode, $card_no, $gender) {
        $this->profileCode = $profileCode;
        $this->card_no = $card_no;
        $this->gender = $gender;
    }

    public function getProfileCode() { return $this->profileCode; }
    public function getCard_no() { return $this->card_no; }
    public function getGender() { return $this->gender; }

    public function mostrar() {
        return "[$this->profileCode] $this->card_no - $this->gender";
    }
}
?>
