<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/15/15
 * Time: 1:23 PM
 */

require_once(dirname(__FILE__).'/../employeeModel.php');
require_once(dirname(__FILE__).'/../db/dbConnectionFactory.php');
require_once(dirname(__FILE__).'/userDAO.php');
class employeeDAO {

    //TODO: Use cache to reduce DB calls.
    private static $employeeCache = array();

    public function __construct()
    {

    }

    public function find($id){
        $connection = DbConnectionFactory::create();
        $query = "SELECT * FROM employee NATURAL JOIN users WHERE id=:id";

        $stmt=$connection->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'employeeModel'); //MAY NEED FETCH_PROPS_LATE FLAG. see below
        $emp=$stmt->fetch();
        $connection=null;

        return $emp;
    }

    public function create_DCP($employee){
        $newEmployee=new userModel($employee->email, $employee->password, $employee->role);
        $userDAO=new userDAO();

        $id=$userDAO->insert($newEmployee);

        $this->insert($employee->name, $employee->facility_id, $id);

    }

    private function insert( $name, $facility_id, $id){
        $connection = DbConnectionFactory::create();

        $query = "INSERT INTO employee (emp_name, facility_id, id) VALUES ( :name, :facility_id, :id)";
        $stmt=$connection->prepare($query);

        $stmt->bindParam(":facility_id", $facility_id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $connection=null;
    }

    public function getFacilityEmployees($facility_id){
        $connection=DbConnectionFactory::create();

        $query = "SELECT * FROM employee WHERE facility_id = :facility_id";
        $stmt=$connection->prepare($query);

        $stmt->bindParam(":facility_id",$facility_id);

        $stmt->execute();

        $employees = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'employeeModel');
        $connection=null;

        return $employees;

    }
}