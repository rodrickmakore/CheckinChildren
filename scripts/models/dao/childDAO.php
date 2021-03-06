<?php

require_once(dirname(__FILE__).'/../childModel.php');
require_once(dirname(__FILE__).'/../db/dbConnectionFactory.php');

/**
 * Class childDAO
 *
 * Manages Child table in database.
 */
class childDAO {

    /**
     * Insert new child
     * @param childModel $child Data for new Child in childModel object
     * @return string ChildId assigned to the new child
     */
    function insert($child){
        $connection = DbConnectionFactory::create();

        $query = "INSERT INTO child (child_id, parent_id, child_name, allergies, trusted_parties, facility_id, expect_checkin, expect_checkout) VALUES ( :child_id, :parent_id, :child_name, :allergies, :trusted_parties, :facility_id, :expect_checkin, :expect_checkout)";
        $stmt=$connection->prepare($query);

        $stmt->bindParam(":child_id", $child->child_id);
        $stmt->bindParam(":parent_id", $child->parent_id);
        $stmt->bindParam(":child_name", $child->child_name);
        $stmt->bindParam(":allergies", $child->allergies);
        $stmt->bindParam(":trusted_parties", $child->trusted_parties);
        $stmt->bindParam(":facility_id", $child->facility_id);
        $stmt->bindParam(":expect_checkin", self::timesArrayToCsv($child->expect_checkin));
        $stmt->bindParam(':expect_checkout', self::timesArrayToCsv($child->expect_checkout));

        $stmt->execute();
        $child_id = $connection->lastInsertId();

        $connection=null;

        return $child_id;
    }

    /**
     * Retrieve child with given id
     * @param int $id Id of the child
     * @return childModel Return child wrapped in childModel
     */
    public function find($id){
        $connection = DbConnectionFactory::create();
        $query = "SELECT * FROM child WHERE child_id=:id";

        $stmt=$connection->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'childModel');
        $child=$stmt->fetch();
        $connection=null;
        if ($child!=false) {
            $child->expect_checkin = self::timesCsvToArray($child->expect_checkin);
            $child->expect_checkout = self::timesCsvToArray($child->expect_checkout);
        }
        return $child;
    }

    /**
     * Retrieve all children in facility with a given id
     * @param int $facilityId Facility id
     * @return array Array of children in the facility with id
     */
    public function findChildrenInFacility($facilityId) {
        $connection = DbConnectionFactory::create();
        $query = 'SELECT * FROM child WHERE facility_id=:facility_id';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':facility_id', $facilityId);
        $stmt->execute();
        $children = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'childModel');
        $connection = null;

        foreach ($children as $child){
            $child->expect_checkin = self::timesCsvToArray($child->expect_checkin);
            $child->expect_checkout = self::timesCsvToArray($child->expect_checkout);
        }
        return $children;
    }

    /**
     * Converts CSV format from the database into an array of times
     * @param String $csv Time in CSV format
     * @return array Array of times
     */
    public static function timesCsvToArray($csv){
        $arr = explode(',',$csv);
        $arr['u'] = $arr[0] = intval($arr[0]);
        $arr['m'] = $arr[1] = intval($arr[1]);
        $arr['t'] = $arr[2] = intval($arr[2]);
        $arr['w'] = $arr[3] = intval($arr[3]);
        $arr['r'] = $arr[4] = intval($arr[4]);
        $arr['f'] = $arr[5] = intval($arr[5]);
        $arr['s'] = $arr[6] = intval($arr[6]);
        return $arr;
    }

    /**
     * Converts array of times to CSV format
     * @param array $arr Time in array
     * @return string Comma separated times in CSV format
     */
    public static function timesArrayToCsv($arr){
        $csv = '';
        for($i=0; $i<6; $i++){
            $csv .= $arr[$i].',';
        }
        $csv .= $arr[6];
        return $csv;
    }

    /**
     * Find children with a given parent id
     * @param int $parent_id Parent id of the child
     * @return array Array of children with a given parent id
     */
    public function findChildrenWithParent($parent_id) {
        $connection=DbConnectionFactory::create();

        $query = "SELECT * FROM child WHERE parent_id = :parent_id ORDER BY child_name ASC";
        $stmt=$connection->prepare($query);

        $stmt->bindParam(":parent_id",$parent_id);
        $stmt->execute();

        $children = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'childModel');
        $connection=null;

        foreach ($children as $child){
            $child->expect_checkin = self::timesCsvToArray($child->expect_checkin);
            $child->expect_checkout = self::timesCsvToArray($child->expect_checkout);
        }

        return $children;
    }

    /**
     * Updates a child's name, allergies, trusted parties, expected check in/ expected checkout, and last message status
     * @param childModel $child Data for the updated child
     */
    public function update($child) {
        $this->updateField($child->child_id, 'child_name', $child->child_name);
        $this->updateField($child->child_id, 'allergies', $child->allergies);
        $this->updateField($child->child_id, 'trusted_parties', $child->trusted_parties);
        $this->updateField($child->child_id, 'expect_checkin', self::timesArrayToCsv($child->expect_checkin));
        $this->updateField($child->child_id, 'expect_checkout', self::timesArrayToCsv($child->expect_checkout));
        $this->updateField($child->child_id, 'last_message_status', $child->last_message_status);
        $this->updateField($child->child_id, 'parent_late_minutes', $child->parent_late_minutes);
    }

    /**
     * Update given field of the child
     * @param int $child_id Child id
     * @param String $field Field to update
     * @param String $value New Value
     */
    public function updateField($child_id, $field, $value){
        $connection = DbConnectionFactory::create();
        $query = 'UPDATE child SET '.$field.'=:value WHERE child_id=:id';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $child_id);
        $stmt->execute();
        $connection = null;
    }

    /**
     * Get all children
     * @return mixed All children
     */
    public function getAllChildrenWithPotentialStatusUpdate(){
        $connection = DbConnectionFactory::create();
        $query = "SELECT * FROM child";
        $stmt=$connection->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'childModel');
        $child=$stmt->fetch();
        $connection=null;
        if ($child!=false) {
            $child->expect_checkin = self::timesCsvToArray($child->expect_checkin);
            $child->expect_checkout = self::timesCsvToArray($child->expect_checkout);
        }
        return $child;
    }

    /**
     * Delete all the children in the facility
     * @param int $facility_id Facility id
     */
    public function deleteAllChildrenInFacility($facility_id){
        $connection = DbConnectionFactory::create();
        $query = "DELETE FROM child WHERE facility_id=:id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $facility_id);
        $stmt->execute();
        $connection = null;
    }

}