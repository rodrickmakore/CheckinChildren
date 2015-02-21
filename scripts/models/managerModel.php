<?php

require_once(dirname(__FILE__).'/userModel.php');
require_once(dirname(__FILE__).'/dao/facilityDAO.php');

class managerModel extends userModel{
    public $emp_name;
    public $facility_id;
    public $company_id;

    public function __construct( $emp_name="", $password="", $facility_id=0, $company_id = 0, $email="", $id=0)
    {
        $this->id=$id;
        $this->emp_name=$emp_name;
        $this->facility_id=$facility_id;
        $this->company_id = $company_id;
        $this->password=$password;
        $this->email=$email;
        $this->role = "manager";
    }

    public function isValid() {
        if (strlen($this->emp_name)>30 || strlen($this->emp_name)<=0) {
            return false;
        }

        return parent::isValid();
    }

}