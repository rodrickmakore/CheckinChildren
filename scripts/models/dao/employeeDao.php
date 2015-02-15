<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/15/15
 * Time: 1:23 PM
 */

class employeeDao {

    //TODO: Use cache to reduce DB calls.
    private static $employeeCache = array();

    public function __construct()
    {

    }

    public function find($id){
        $connection = DbConnectionFactory::create();
        $query = "SELECT * FROM employee WHERE id=:id";

        $stmt=$this->$connection->prepare($query);
        $stmt->bindParam(':id', $id);

        $result=$stmt->execute();

        $connection=null;

        return new employeeModel($result);
    }

    public function create_employee($emp_info){
        $connection = DbConnectionFactory::create();

        $query = "INSERT INTO user (pass, roll) VALUES(:pass, 'employee')";
        $stmt=$connection->prepare($query);
        $stmt->bindParam(':pass', $emp_info[0]);

        $stmt->execute();
        $id=$stmt->lastInsertId();


    }

    private function insert($employee){
        $connection = DbConnectionFactory::create();
        $query = "INSERT INTO employee ";
        $this->$connection->prepare($query);
        $this->$connection->execute($query);
    }
}