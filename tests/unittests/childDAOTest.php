<?php

require_once(dirname(__FILE__).'/../../scripts/models/dao/childDAO.php');
require_once(dirname(__FILE__).'/UnitTestBase.php');

class childDAOTest extends unitTestBase {

    public function testFind()
    {
        $childDAO=new childDAO();
        $child = $childDAO->find(2);

        $this->assertEquals(2, $child->child_id);
        $this->assertEquals(8, $child->parent_id);
        $this->assertEquals("Mark Zuckerberg", $child->child_name);
        $this->assertEquals("Peanut Butter", $child->allergies);
        $this->assertEquals(1, $child->facility_id);
    }

    public function testCreate_Child()
    {
        $childDAO = new ChildDAO();

        $childTest = new childModel(8, "Red Ranger", "None", 1);
        $child_id = $childDAO->insert($childTest);

        $childFound=$childDAO->find($child_id);

        $this->assertEquals($child_id, $childFound->child_id);
        $this->assertEquals(8, $childFound->parent_id);
        $this->assertEquals("Red Ranger", $childFound->child_name);
        $this->assertEquals("None", $childFound->allergies);
    }

    public function testFindBadID()
    {
        $childDAO = new ChildDAO();
        $child=$childDAO->find(999999);

        $this->assertFalse($child);
    }

    //Update the child with id 2
    public function testUpdate()
    {
        $childDAO = new childDAO();

        $childUpdates = new childModel(0, "New Name", "New Allergies", 0, 2); //the updates to make, id and facility id are not updated
        $childDAO->update($childUpdates);

        $childUpdated = $childDAO->find(2);

        $this->assertEquals(2, $childUpdated->child_id); //id does is not updated
        $this->assertEquals(8, $childUpdated->parent_id); //parent does is not updated
        $this->assertEquals("New Name", $childUpdated->child_name);
        $this->assertEquals("New Allergies", $childUpdated->allergies);
        $this->assertEquals(1, $childUpdated->facility_id); //facility id is not updated
    }

}
