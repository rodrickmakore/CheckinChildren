<?php
/**
 * Created by PhpStorm.
 * User: matt
 */

require_once(dirname(__FILE__).'/../userModel.php');
require_once(dirname(__FILE__).'/../db/dbConnectionFactory.php');

class UserDAO
{

    public function find($field, $value){
        $connection = DbConnectionFactory::create();
        $query = 'SELECT * FROM users WHERE '.$field.'=:val';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':val', $value);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'userModel');
        $user = $stmt->fetch();
        $connection = null;
        return $user;
    }


    public  function insert($user){
        $connection = DbConnectionFactory::create();
        $query = 'INSERT INTO users (email, password, role) VALUES (:email, :password, :role)';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':role', $user->role);
        $stmt->execute();
        $id = $connection->lastInsertId();
        $connection = null;
        return $id;
    }


    public function updateField($userId, $field, $value){
        $connection = DbConnectionFactory::create();
        $query = 'UPDATE users SET '.$field.'=:value WHERE id=:id';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $connection = null;
    }

    public function delete($userId){
        $connection = DbConnectionFactory::create();
        $query = "DELETE FROM users WHERE id=:id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $connection = null;
    }

    public function deleteUsersOfFacility($facilityID)
    {
        $connection = DbConnectionFactory::create();
        $query = "DELETE FROM users WHERE EXISTS (SELECT * FROM employee WHERE facility_id = :id and employee.id = users.id)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $facilityId);
        $stmt->execute();
        $connection = null;
    }

}
