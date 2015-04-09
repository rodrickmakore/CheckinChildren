<?php

require_once dirname(__FILE__).'/../SeleniumTestBase.php';
require_once dirname(__FILE__).'/../TestMacros.php';

class CompanyTest extends SeleniumTestBase
{
    public function setUp(){
        parent::setUp();
    }

    public function testViewFacility() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_facilities")->click();
        $page = $this->driver->get_source();
        $this->assertContains("My Facilities", $page);
        $this->assertContains("2 Facility Rd. Champaign IL 61820", $page);

        $this->get_element("link=2 Facility Rd. Champaign IL 61820")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Facility ID: 2", $page);
        $this->assertContains("Company ID: 1", $page);
        $this->assertContains("Address: 2 Facility Rd. Champaign IL 61820", $page);
        $this->assertContains("Phone: 1235933945", $page);
    }
    public function testViewProfile() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_company_profile")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Company 1", $page);
        $this->assertContains("1 Fake St.", $page);
        $this->assertContains("Champaign IL 61820", $page);
        $this->assertContains("bigcompany1@gmail.com", $page);
        $this->assertContains("8471234567", $page);
    }
    public function testEditProfile() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_company_profile")->click();

        $this->get_element("id=edit_company")->click();

        $this->get_element("id=company_name")->clear();
        $this->get_element("id=company_name")->send_keys("Name Change");
        $this->get_element("id=email")->clear();
        $this->get_element("id=email")->send_keys("email@change.com");
        $this->get_element("id=address")->clear();
        $this->get_element("id=address")->send_keys("Change Address");
        $this->get_element("id=phone_number")->clear();
        $this->get_element("id=phone_number")->send_keys("7651237890");
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Name Change", $page);
        $this->assertContains("email@change.com", $page);
        $this->assertContains("Change Address", $page);
        $this->assertContains("7651237890", $page);
    }
    public function testCreateNewFacility() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("name=view_facilities")->click();
        $this->get_element("name=new_facility")->click();

        $this->get_element("name=address")->send_keys("1091 Huntington Rd Carol Stream Il 60082");
        $this->get_element("name=phone_number")->send_keys("8472728096");
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();

        //assert that the single facility page is shown
        $this->assertContains("Address: 1091 Huntington Rd Carol Stream Il 60082", $page);
        $this->assertContains("Phone: 8472728096", $page);

        //assert that the new facility is shown in the facilities list
        $this->get_element("name=view_facilities")->click();
        $page = $this->driver->get_source();

        $this->assertContains("1091 Huntington Rd Carol Stream Il 60082", $page);

    }
    public function testEditFacility() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_facilities")->click();
        $page = $this->driver->get_source();
        $this->assertContains("My Facilities", $page);
        $this->assertContains("2 Facility Rd. Champaign IL 61820", $page);

        $this->get_element("link=2 Facility Rd. Champaign IL 61820")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Facility ID: 2", $page);
        $this->assertContains("Company ID: 1", $page);
        $this->assertContains("Address: 2 Facility Rd. Champaign IL 61820", $page);
        $this->assertContains("Phone: 1235933945", $page);

        $this->get_element("name=edit_facility")->click();
        $this->get_element("id=address")->clear();
        $this->get_element("id=address")->send_keys("22 EditFacility Rd. Champaign IL 61820");

        $this->get_element("id=phone")->clear();
        $this->get_element("id=phone")->send_keys("1231237890");
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Facility ID: 2", $page);
        $this->assertContains("Company ID: 1", $page);
        $this->assertContains("Address: 22 EditFacility Rd. Champaign IL 61820", $page);
        $this->assertContains("Phone: 1231237890", $page);
    }
    public function testEditFacilityInvalid() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_facilities")->click();
        $page = $this->driver->get_source();
        $this->assertContains("My Facilities", $page);
        $this->assertContains("1 Facility Rd. Champaign IL 61820", $page);

        $this->get_element("link=1 Facility Rd. Champaign IL 61820")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Facility ID: 1", $page);
        $this->assertContains("Company ID: 1", $page);
        $this->assertContains("Address: 1 Facility Rd. Champaign IL 61820", $page);
        $this->assertContains("Phone: 1235933945", $page);

        $this->get_element("name=edit_facility")->click();


        $this->get_element("id=address")->clear();
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Invalid Information", $page);

        $this->get_element("id=phone")->clear();
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Invalid Information", $page);

        $this->get_element("id=phone")->clear();
        $this->get_element("id=phone")->send_keys("1231237890565");
        $this->get_element("name=submit")->click();
        $page = $this->driver->get_source();
        $this->assertContains("Invalid Information", $page);
    }
    public function testDeleteFacility() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_facilities")->click();
        $page = $this->driver->get_source();
        $this->assertContains("My Facilities", $page);
        $this->assertContains("1 Facility Rd. Champaign IL 61820", $page);

        $this->get_element("link=1 Facility Rd. Champaign IL 61820")->click();

        $page = $this->driver->get_source();
        $this->assertContains("Facility ID: 1", $page);
        $this->assertContains("Company ID: 1", $page);
        $this->assertContains("Address: 1 Facility Rd. Champaign IL 61820", $page);
        $this->assertContains("Phone: 1235933945", $page);

        $this->get_element("id=delete_facility")->click();

        $this->get_element("id=modal-submit")->click();
        $this->assertContains("My Facilities", $page);
        $this->assertNOtContains("1 Facility Rd. Champaign IL 61820", $page); //@TODO How do I check that it was removed?

    }
    public function testViewManagers() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("signed-in")->assert_text("Currently signed in as a company");

        $this->get_element("name=view_managers")->click();
        $page = $this->driver->get_source();
        $this->assertContains("Managers", $page);
        $this->assertContains("Bob Dude", $page);
        $this->assertContains("Rick Grimes", $page);
        $this->assertContains("1", $page);
        $this->assertContains("2", $page);
    }

    public function testCreateNewManager() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("name=view_managers")->click();
        $this->get_element("name=create_manager")->click();

        $this->get_element("name=name")->send_keys("Test Man");
        $this->get_element("name=facility_id")->send_keys("1");
        $this->get_element("name=email")->send_keys("test@mail.com");
        $this->get_element("name=password")->send_keys("password1");
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();

        //assert that the single facility page is shown
        $this->assertContains("Test Man", $page);
        $this->assertContains("1", $page);
    }

    public function testCreateNewInvalidManager() {
        testMacros::login($this->driver, "bigcompany1@gmail.com", "password1");

        $this->get_element("name=view_managers")->click();
        $this->get_element("link=Create A New Manager")->click();

        $this->get_element("name=name")->send_keys("Test Man");
        $this->get_element("name=facility_id")->send_keys("123");
        $this->get_element("name=email")->send_keys("test@mail.com");
        $this->get_element("name=password")->send_keys("password1");
        $this->get_element("name=submit")->click();

        $page = $this->driver->get_source();

        //assert that the single facility page is shown
        $this->assertContains("Invalid Information", $page);
    }

    public function tearDown(){
        parent::tearDown();
    }
}