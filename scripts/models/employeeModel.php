<?php
require_once(dirname(__FILE__).'/userModel.php');

class employeeModel extends userModel{
    public $emp_name;
    public $facility_id;

    public function __construct( $emp_name="", $password="", $facility_id=0, $email="", $role="", $id=0)
    {
        $this->id=$id;
        $this->emp_name=$emp_name;
        $this->facility_id=$facility_id;
        $this->password=$password;
        $this->email=$email;
        $this->role=$role;
    }

    public function isValid() {

        return $this->isUpdateValid() && parent::isValid();
    }

    public function isUpdateValid() {
        if (strlen($this->emp_name) > 30 || strlen($this->emp_name)<=0) {
            return false;
        }

        return parent::isUpdateValid();
    }

}