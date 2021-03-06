<?php

require_once dirname(__FILE__).'/../SeleniumTestBase.php';
require_once dirname(__FILE__).'/../TestMacros.php';

class CreateCompanyTest extends SeleniumTestBase
{
    public function testCreateCompany() {
        //I am redirected to login page
        $this->assert_title("Login");

        //Go to CreateCompany page
        $this->get_element("name=signup")->click();

        //Create a new company
        testMacros::createCompany($this->driver, "Test Company 1", "123 Fake Dr", "1234567890", "newcompany@gmail.com", "password1");
        $this->assert_title("Login");

        //Login as new company
        testMacros::login($this->driver, "newcompany@gmail.com", "password1");

        //View Facilities and Managers
        $this->get_element("name=view_facilities")->click();
        $this->get_element("name=home")->click();
        $this->get_element("name=view_managers")->click();
        $this->get_element("id=title")->assert_text("Managers");

    }
    public function testCreateCompanyInvalid()
    {
        //I am redirected to login page
        $this->assert_title("Login");

        //Go to CreateCompany page
        $this->get_element("name=signup")->click();

        //Create a new company
        testMacros::createCompany($this->driver, "Test Company 1", "", "1234567890", "newcompany@gmail.com", "password1");

        $error_msg = $this->get_element("id=error_message")->get_text();
        $this->assertContains("Invalid Address", $error_msg);
    }
}