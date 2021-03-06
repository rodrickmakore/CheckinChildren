<?php

require_once(dirname(__FILE__).'/../../scripts/models/dao/childDAO.php');
require_once(dirname(__FILE__).'/UnitTestBase.php');

/**
 * Class childDAOTest
 *
 * Test childDAO
 */
class childDaoTest extends unitTestBase {

    /**
     * Tests find function
     */
    public function testFind()
    {
        $childDAO=new childDAO();
        $child = $childDAO->find(2);

        $this->assertEquals(2, $child->child_id);
        $this->assertEquals(8, $child->parent_id);
        $this->assertEquals("Mark Zuckerberg", $child->child_name);
        $this->assertEquals("Peanut Butter", $child->allergies);
        $this->assertEquals("Chmiel", $child->trusted_parties);
        $this->assertEquals(1, $child->facility_id);
    }

    /**
     * Test create child function
     */
    public function testCreate_Child()
    {
        $childDAO = new childDAO();

        $childTest = new childModel(8, "Red Ranger", "None", "Tom", 1);
        $child_id = $childDAO->insert($childTest);

        $childFound=$childDAO->find($child_id);

        $this->assertEquals($child_id, $childFound->child_id);
        $this->assertEquals(8, $childFound->parent_id);
        $this->assertEquals("Red Ranger", $childFound->child_name);
        $this->assertEquals("None", $childFound->allergies);
        $this->assertEquals("Tom", $childFound->trusted_parties);
    }

    /**
     * Test with bad id
     */
    public function testFindBadID()
    {
        $childDAO = new childDAO();
        $child=$childDAO->find(999999);

        $this->assertFalse($child);
    }

    /**
     * Update the child with id 2
     */
    public function testUpdate()
    {
        $childDAO = new childDAO();

        $childUpdates = new childModel(0, "New Name", "New Allergies", "Tom", 0, 2); //the updates to make, id and facility id are not updated
        $childUpdates->expect_checkin=array(20,20,20,20,20,20,20);
        $childUpdates->expect_checkout=array(200,200,200,200,200,200,200);
        $childUpdates->last_checkin="2015-03-04 12:15:48";
        $childUpdates->last_checkout="2015-03-04 14:49:30";
        $childDAO->update($childUpdates);

        $childUpdated = $childDAO->find(2);

        $this->assertEquals(2, $childUpdated->child_id); //id does is not updated
        $this->assertEquals(8, $childUpdated->parent_id); //parent does is not updated
        $this->assertEquals("New Name", $childUpdated->child_name);
        $this->assertEquals("New Allergies", $childUpdated->allergies);
        $this->assertEquals("Tom", $childUpdated->trusted_parties);
        $this->assertEquals(1, $childUpdated->facility_id); //facility id is not updated
        $arr=[];
        $arr['u'] = $arr[0] = 20;
        $arr['m'] = $arr[1] = 20;
        $arr['t'] = $arr[2] = 20;
        $arr['w'] = $arr[3] = 20;
        $arr['r'] = $arr[4] = 20;
        $arr['f'] = $arr[5] = 20;
        $arr['s'] = $arr[6] = 20;
        $this->assertEquals($arr, $childUpdated->expect_checkin);
        $arr=[];
        $arr['u'] = $arr[0] = 200;
        $arr['m'] = $arr[1] = 200;
        $arr['t'] = $arr[2] = 200;
        $arr['w'] = $arr[3] = 200;
        $arr['r'] = $arr[4] = 200;
        $arr['f'] = $arr[5] = 200;
        $arr['s'] = $arr[6] = 200;

        $this->assertEquals($arr, $childUpdated->expect_checkout);
        //Checkin/Out times must be updated separately
        $this->assertEquals("0000-00-00 00:00:00", $childUpdated->last_checkout);
        $this->assertEquals("0000-00-00 00:00:00", $childUpdated->last_checkin);

        $childDAO->updateField(2, 'last_checkin', $childUpdates->last_checkin);
        $childDAO->updateField(2, 'last_checkout', $childUpdates->last_checkout);

        $childUpdated = $childDAO->find(2);
        $this->assertEquals($childUpdates->last_checkout, $childUpdated->last_checkout);
        $this->assertEquals($childUpdates->last_checkin, $childUpdated->last_checkin);

    }

    /**
     * Test delete all children from facility
     */
    public function testDeleteAllChildrenInFacility(){
        $childDAO = new childDAO();
        $children = $childDAO->findChildrenInFacility(5);
        $this->assertEquals(10,count($children));

        $childDAO->deleteAllChildrenInFacility(5);
        $children = $childDAO->findChildrenInFacility(5);
        $this->assertEquals(0,count($children));
    }

}
