<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/4/15
 * Time: 10:36 PM
 */

class CheckChildInOutTest extends SeleniumTestBase{

    public function setUp(){
        parent::setUp();
    }

    public function testCheckChildIn(){
        $this->get_element("name=email")->send_keys("employee17@gmail.com");
        $this->get_element("name=password")->send_keys("password17");
        $this->get_element("name=submit")->click();

        $this->get_element("id=checkin_children")->click();


    }

}