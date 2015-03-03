<?php
/**
 * Created by PhpStorm.
 * User: Elzabad
 */
require_once(dirname(__FILE__).'/../companyModel.php');
require_once(dirname(__FILE__).'/../facilityModel.php');
require_once(dirname(__FILE__).'/../db/dbConnectionFactory.php');
require_once(dirname(__FILE__).'/userDAO.php');

class CompanyDAO {
    public function createCompany(companyModel $company){
        $newCompany=new userModel($company->email, $company->password, $company->role);
        $userDAO=new userDAO();

        $id = $userDAO->insert($newCompany);

        $this->insert($company, $id);
        return $id;
    }
    //Inserts the specified company in the data base
    public function insert($company, $id) {
        $connection = DbConnectionFactory::create();
        $query = 'INSERT INTO company (id, company_name, address, phone) VALUES (:id,:company_name, :address, :phone)';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':company_name', $company->company_name);
        $stmt->bindParam(':address', $company->address);
        $stmt->bindParam(':phone', $company->phone);
        $stmt->execute();
        $facility_id = $connection->lastInsertId();
        $connection = null;
        return $facility_id;
    }

    //Retreives a company matching the specified facility id
    //Returns false if a company is not found
    public function find($id) {
        $connection = DbConnectionFactory::create();
        $query = 'SELECT * FROM company NATURAL JOIN users WHERE id=:id';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'companyModel');
        $company = $stmt->fetch();
        $connection = null;
        return $company;
    }
}